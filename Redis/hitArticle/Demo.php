<?php
/**
 * Function:
 * Description:
 * Abo 2019/10/15 19:51
 * Email: abo2013@foxmail.com
 */

require_once __DIR__.'/../code/RedisUtil.php';

class Demo
{
    const HIT_RATE = 4320;

    private $RedisUtil;
    private $articles = [
        [ 'id' => 1, 'title' => 'article 1', 'link' => 'http://article 1', 'user' => 'xx', 'votes' => 0, 'publish_time' => 1571190843 ],
        [ 'id' => 2, 'title' => 'article 2', 'link' => 'http://article 2', 'user' => 'xx', 'votes' => 0, 'publish_time' => 1571190903 ],
        [ 'id' => 3, 'title' => 'article 3', 'link' => 'http://article 3', 'user' => 'xx', 'votes' => 0, 'publish_time' => 1571190963 ],
        [ 'id' => 4, 'title' => 'article 4', 'link' => 'http://article 4', 'user' => 'xx', 'votes' => 0, 'publish_time' => 1571191023 ],
        [ 'id' => 5, 'title' => 'article 5', 'link' => 'http://article 5', 'user' => 'xx', 'votes' => 0, 'publish_time' => 1571191083 ],
    ];

    public function __construct()
    {
        $this->RedisUtil = new RedisUtil();
    }

    public function testRedis()
    {
        $RedisUtil = new RedisUtil();

        $RedisUtil->set( 'test', '10086' );
        $RedisUtil->expire( 'test', 1 );
        return $RedisUtil->get( 'test' );
    }

    /**
     * 添加文章列表
     * @return int
     */
    public function initArticles()
    {
        $i2Count = 0;
        $currentTime = time();

        try {
            foreach ($this->articles as &$v2Article) {
                $v2Article['time'] = $currentTime + ($v2Article['id'] * 60); // 模拟先后发送时间：1最早 5最晚

                // 文章列表
                $this->RedisUtil->hMset('articles:' . $v2Article['id'], $v2Article);

                // 文章发布时间列表
                $this->RedisUtil->zAdd('articles_publish_time:' . $v2Article['id'], $v2Article['time'], $v2Article['id']);

                // 文章热度评分列表
                $this->RedisUtil->zAdd('articles_hit_rate:' . $v2Article['id'], $v2Article['time'], $v2Article['id']);

                $i2Count++;
            }
        } catch (Exception $e) {
            throw new Exception( $e->getMessage(), false );
        }

        return $i2Count;
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
        $ret2Return = $this->RedisUtil->hMset('articles:' . $articleInfo['id'], $articleInfo);

        // 文章发布时间列表
        $this->RedisUtil->zAdd('articles_publish_time:' . $articleInfo['id'], $articleInfo['time'], $articleInfo['id']);

        // 文章热度评分列表
        $this->RedisUtil->zAdd('articles_hit_rate:' . $articleInfo['id'], $articleInfo['time'], $articleInfo['id']);

        return $ret2Return;
    }
}


$Demo = new Demo();
echo $Demo->initArticles();

// todo 文章发布时间列表
// todo 文章评分列表
// 点赞 & 避免重复点赞
// 添加文章
// 获取文章