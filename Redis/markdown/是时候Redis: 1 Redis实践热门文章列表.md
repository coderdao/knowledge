> 本文为Redis系列开始，全程代码实践干货满满，喜欢可以关注我

## 场景
像博客、论坛等内容网站让优质内容得到足够曝光，是提高网站吸引力的重要方法。
今天我们就经常占据网站C位的 `热门文章列表` 这一场景来详细分析。
![ 慕课网-手记 热门文章 ](//img.mukewang.com/5da6ff1f00014fb104560351.png)


首先产品经理来找你谈( wa )需( keng )求：
![图片描述](//img.mukewang.com/5da7053000010c6005120360.png)
> 玩笑归玩笑哈，多维度的综合排序确实时常遇到~ 怎么办呢？

## 核心思路
我们引出一个 `热度` 的概念，它其实是个代表文章的受欢迎程度的`分数`。
我们把 发布时间、点赞、评论、浏览量... 通过公式转化为热度，再根据它来排序即可

## 实践
文章按时间倒序排序，我们可以理解了一个`随时间衰减`的评分，这里可以使用Unix时间。而点赞、评论、浏览量...则乘以自己的权重(`常量`)，加上发布时间就等于文章评分

### 时间衰减评分
首先准备`文章数据`：id:1文章最早发布，id:5文章最晚
```
[ 'id' => 1, 'title' => 'article 1', 'link' => 'http://article 1', 'user_id' => 1, 'votes' => 0, 'publish_time' => 1571190843 ],
[ 'id' => 2, 'title' => 'article 2', 'link' => 'http://article 2', 'user_id' => 1, 'votes' => 0, 'publish_time' => 1571190903 ],
[ 'id' => 3, 'title' => 'article 3', 'link' => 'http://article 3', 'user_id' => 1, 'votes' => 0, 'publish_time' => 1571190963 ],
[ 'id' => 4, 'title' => 'article 4', 'link' => 'http://article 4', 'user_id' => 1, 'votes' => 0, 'publish_time' => 1571191023 ],
[ 'id' => 5, 'title' => 'article 5', 'link' => 'http://article 5', 'user_id' => 1, 'votes' => 0, 'publish_time' => 1571191083 ]
```

同时在Redis建立起 `articles_hit_rate` 文章热度 的有序列表(zset)
![图片描述](//img.mukewang.com/5da70f3b000150fd03890230.png)
>article 是文章id, score 为 热度评分

测试`zrevrangebyscore  articles_hit_rate +inf -inf` 根据分数倒序取值。结果;
```
local_redis:0>zrevrangebyscore  articles_hit_rate +inf -inf
 1)  "5"
 2)  "4"
 3)  "3"
 4)  "2"
 5)  "1"
```
[zrevrangebyscore文档](http://redisdoc.com/sorted_set/zrevrangebyscore.html)

### 点赞👍
点赞、评论、浏览量的权重（常量），有个很好的计算方式：
发布一天内，你认为获得多少点赞👍的文章是优质文章/列表首页展示多少条数据。（ 比如 100 ）
权重常量：86400 / 100 = 864   （ 一天有86400秒 ）

```
    /**
     * 点赞文章
     * @param int $articleId 文章id
     * @param int $userId	 用户id
     * @param int $voteNum	 点赞数
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
```
至于其他维度，也可以按照点赞方法以此类推...
当然为了防止用户对同一片文章进行多次投票，还需要用Redis中无序列表set为每篇文章建立已投票用户。这个在代码里面有提现，这里就不过多讨论...

## 源代码
[源代码链接](https://github.com/ruidao/demo/tree/master/Redis/code)
