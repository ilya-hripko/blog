<div class="row">
    <div class="col-md-8">
        <h1 class="title"><?php echo $h1 ?></h1>
        <?=$text_html?>
    </div>
    <div class="col-md-4">
        <div class="block-title">Contact Form</div>
        <form action="<?=$base_url?>contacts/">
            <div class="form-group">
                <label>Name</label>
                <input class="form-control" type="text" name="name"/>
            </div>
            <div class="form-group">
                <label>E-mail</label>
                <input class="form-control" type="text" name="email"/>
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input class="form-control" type="text" name="phone"/>
            </div>
            <div class="form-group">
                <label>Message</label>
                <textarea class="form-control" name="message" style="height: 200px;"></textarea>
            </div>
            <a href="#" class="btn btn-danger">CLEAR</a>
            <button type="submit" class="btn btn-danger">SEND</button>
        </form>
    </div>
</div>
