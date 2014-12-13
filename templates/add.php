<div class="row">
    <div id="add-form">
        <h2>Add Application</h2>
        <?php
        if ($user->isSigned()) {
            if($user->GroupID < $user::DEVELOPER){ ?>
                <span class="alert alert-danger">You do not have access to this page</span>
            <?php }
            else { ?>
            <form action="/" method="post">
                <label>Title
                <input type="text" class="form-control" name="title" placeholder="Application Title" autofocus></label>
                <br>
                <label>Developer
                <input type="text" class="form-control" name="developer" placeholder="Developer name"></label>
                <br>
                <label>Price
                <span style="position: relative; top: 27px; left: -34px;">$</span><input type="text" class="form-control" name="price" placeholder="1.99" style="padding-left: 16px;"></label>
                <br>
                <label>Category
                <input type="text" class="form-control" name="category" placeholder="Uncategorized"></label>
                <br>
                <label>Compatible Platforms</label><br>
                <input type="checkbox" name="compatibleApple" value="true"> Apple <br>
                <input type="url" class="form-control" name="linkApple" placeholder="https://itunes.apple.com/us/app/">
                <input type="checkbox" name="compatibleAndroid" value="true"> Android <br>
                <input type="url" class="form-control" name="linkAndroid" placeholder="https://play.google.com/store/apps/details">
                <input type="checkbox" name="compatibleWindows" value="true"> Windows
                <input type="url" class="form-control" name="linkWindows" placeholder="http://www.windowsphone.com/en-us/store/app">
                <br>
                <label>Link to developer app page
                <input type="url" class="form-control" name="developer_link" placeholder="http://example.com/"></label>
                <br>
                <label>Keywords (comma separated)
                <input type="text" class="form-control" name="keywords" placeholder="keyword1, keyword2, etc"></label>
                <br>
                <label>Link to application image
                <input type="url" class="form-control" name="image_link" placeholder="http://example.com/image.png"></label>
                <br>
                <label>Application description
                <textarea class="form-control" name="description"></textarea></label>
                <br>
                <input type="submit" class="btn btn-default" name="action" value="Add Application">
            </form>
        <?php }
        }
        else { ?>
            <span class="alert alert-danger">You must be logged in to view this page</span>
        <?php } ?>
    </div>
</div>