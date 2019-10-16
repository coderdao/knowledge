<?php
/**
 * Function:
 * Description:
 * Abo 2019/10/15 19:51
 * Email: abo2013@foxmail.com
 */

require_once __DIR__ . '/../RedisUtil.php';

class Demo
{
    const LIKE_HIT_RATE = 4320; // 点赞常量
    const LIST_ARTICLE_PREFIXX = 'articles:';
    const LIST_ARTICLE_VOTE_PREFIXX = 'articles_vote:';

    const LIST_ARTICLE_PUBLISH_TIME = 'articles_publish_time';
    const LIST_ARTICLE_HIT_RATE = 'articles_hit_rate';

    private $RedisUtil;
    private $articles = [
        [ 'id' => 1, 'title' => 'article 1', 'link' => 'http://article 1', 'user_id' => 1, 'votes' => 0, 'publish_time' => 1571190843 ],
        [ 'id' => 2, 'title' => 'article 2', 'link' => 'http://article 2', 'user_id' => 1, 'votes' => 0, 'publish_time' => 1571190903 ],
        [ 'id' => 3, 'title' => 'article 3', 'link' => 'http://article 3', 'user_id' => 1, 'votes' => 0, 'publish_time' => 1571190963 ],
        [ 'id' => 4, 'title' => 'article 4', 'link' => 'http://article 4', 'user_id' => 1, 'votes' => 0, 'publish_time' => 1571191023 ],
        [ 'id' => 5, 'title' => 'article 5', 'link' => 'http://article 5', 'user_id' => 1, 'votes' => 0, 'publish_time' => 1571191083 ]
    ];

    public function __construct()
    {
        $this->RedisUtil = new RedisUtil();
    }

    public function newestArticle()
    {
        $article2Scoures = $this->RedisUtil->zRevrangebyscore( self::LIST_ARTICLE_PUBLISH_TIME, [ 'withscores' => true ] );
        return $this->getArticleById( array_keys( $article2Scoures ) );
    }

    public function hitestArticle()
    {
        $article2Scoures = $this->RedisUtil->zRevrangebyscore( self::LIST_ARTICLE_HIT_RATE, [ 'withscores' => true ] );
        return $this->getArticleById( array_keys( $article2Scoures ) );
    }

    /**
     * 点赞文章
     * @param int $articleId
     * @param int $userId
     * @param int $voteNum
     * @return bool
     */
    public function voteArticle( int $articleId, int $userId, int $voteNum )
    {
        if ( $this->isVoted( $articleId, $userId ) ) return false; // 已投票，返回
        $this->userVoted( $articleId, $userId );

        $this->RedisUtil->hIncrBy( self::LIST_ARTICLE_PREFIXX . $articleId, 'votes', $voteNum ); // 更新文章点赞数
        $this->RedisUtil->zinCrBy( self::LIST_ARTICLE_HIT_RATE, ( $voteNum * self::LIKE_HIT_RATE ), $articleId ); // 更新文章热度

        return true;
    }

    /**
     * 初始化文章数据
     * @return bool|int
     * @throws Exception
     */
    public function initArticles()
    {
        $i2Count = 0;
        $currentTime = time();

        try {
            foreach ($this->articles as &$v2Article) {
                $v2Article['time'] = $currentTime + ($v2Article['id'] * 60); // 模拟先后发送时间：1最早 5最晚

                $i2Count += $this->publishArticle( $v2Article );
            }
        } catch (Exception $e) {
            throw new Exception( $e->getMessage(), false );
        }

        return $i2Count;
    }

    protected function getArticleById( $articleId )
    {
        $ret2Return = [];

        if ( is_int( $articleId ) ) {
            $ret2Return = $this->RedisUtil->hMget(
                self::LIST_ARTICLE_PREFIXX . $articleId
                , [ 'id', 'title', 'link', 'user_id', 'votes', 'publish_time' ]
            );
        } elseif ( is_array( $articleId ) ) {
            foreach ( $articleId as $v2ArticleId ) {
                $ret2Return[] = $this->getArticleById( $v2ArticleId );
            }
        }

        return $ret2Return;
    }

    /**
     * 是否已点赞
     * @param int $articleId
     * @param int $userId
     * @return bool
     */
    protected function isVoted( int $articleId, int $userId )
    {
        return $this->RedisUtil->sIsMember( self::LIST_ARTICLE_VOTE_PREFIXX.$articleId, $userId );
    }

    /**
     * 用户点赞记录
     * @param int $articleId
     * @param int $userId
     * @return bool
     */
    protected function userVoted( int $articleId, int $userId )
    {
        return $this->RedisUtil->sAdd( self::LIST_ARTICLE_VOTE_PREFIXX.$articleId, $userId );
    }

    /**
     * 发布文章
     * @param array $articleInfo 单篇文章信息
     * @return bool
     */
    protected function publishArticle( array $articleInfo ):bool
    {
        $ret2Return = false;
        if ( !$articleInfo ) return $ret2Return;

        // 文章列表
        $ret2Return = $this->RedisUtil->hMset( self::LIST_ARTICLE_PREFIXX . $articleInfo['id'], $articleInfo);

        // 文章发布时间列表
        $this->RedisUtil->zAdd(self::LIST_ARTICLE_PUBLISH_TIME, $articleInfo['time'], $articleInfo['id']);

        // 文章热度评分列表
        $this->RedisUtil->zAdd(self::LIST_ARTICLE_HIT_RATE, $articleInfo['time'], $articleInfo['id']);

        return $ret2Return;
    }

    public static function dd( $param ){
        echo '<pre>';
        exit( var_dump( $param ) );
    }
}


$Demo = new Demo();
// echo $Demo->initArticles();
// echo "\r\n";
//print_r( $Demo->newestArticle() );
//
//echo "\r\n";
print_r( $Demo->hitestArticle() );
//
//echo "\r\n";
//$Demo->voteArticle( 4, 1, 1 ); // 点赞 & 避免重复点赞
//print_r( $Demo->hitestArticle() );


// 添加文章
// 获取文章