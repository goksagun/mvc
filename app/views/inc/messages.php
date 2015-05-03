<?php if (App\Flash::has('messages')): ?>
    <?php foreach (App\Flash::get('messages') as $type => $error): ?>
        <div class="alert alert-<?php echo $type ?> alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo $error ?>
        </div>
    <?php endforeach ?>
<?php endif ?>