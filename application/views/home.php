<?php $this->load->view('header');?>
    <div class="wrap">
        <div class="content">
            <div class="main">
                <h1 class="sitetit">每周特价情报：</h1>
                <div class="submain">
                    <ul class="feed-list">
                    <?php foreach($feed_list as $feed): ?>
                        <li class="feed-item">
                             <div class="feed-img">
                                <?php if(!empty($feed['thumbnail_pic'])): ?>
                                    <img src=<?php echo $feed['thumbnail_pic']; ?>>
                                <?php endif; ?>
                            </div>
                            <div class="feed-content"><?php echo $feed['text'];?>
<span style="font-size:12px;line-height:30px;color:#ccc;display:block;">发布时间：<?php echo $feed['created_at'];?></span>
                            </div>
                        </li>
<?php endforeach; ?>
                    </ul>

                </div>
            </div>
            <div class="sidebar">
                <div class="section">
                    <div class="sitetip">google广告</div>
                    <div class="box">
                        <div class="box-content">
							<script type="text/javascript"><!--
							google_ad_client = "ca-pub-6678851577500910";
							/* 旅游特价信息首页 */
							google_ad_slot = "9419223987";
							google_ad_width = 250;
							google_ad_height = 250;
							//-->
							</script>
							<script type="text/javascript"
							src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
							</script>
</div>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <div class="footer">
        <div class="footer-sub">

            版权所有 &copy; 2010 - 2011 旅游特价情报站 &nbsp;&nbsp;  业务合作:<a href="http://weibo.com/happyweekend" target="_blank">weibo.com/happyweekend</a>
        </div>
    </div>
</body>
</html>
    
