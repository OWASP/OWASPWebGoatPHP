<?php if (jf::$RunMode->IsEmbed()) return;?>

<!--contact modal
==================-->
<div class="modal fade" id="contact">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Contact Us</h2>
            </div>
            <div class="modal-body">
                <p>If you have any queries please write to us at
                     <span style="color:#d2322d">webgoatphp at owasp dot org</span></p>
                <p>Thank You</p>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-default" data-dismiss="modal">Close</a>
            </div>
        </div>
    </div>
</div>

<div id="pop-up-div" class="reveal-modal large">
    <a class="close-reveal-modal">&#215;</a>
    <div class="ajax-content"></div>
</div>

<script type="text/javascript" src="<?php echo jf::url()?>/script/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo jf::url()?>/script/prettify/prettify.js"></script>
<script type="text/javascript" src="<?php echo jf::url()?>/script/jquery.reveal.js"></script>
<script type="text/javascript" src="<?php echo jf::url()?>/script/challenges.js"></script>
</body>
</html>