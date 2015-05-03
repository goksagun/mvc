<?php include views_path('inc/header.php') ?>
    <div class="container">
    	<div class="page-header clearfix">
            <h1 class="pull-left">Create New User</h1>
            <a class="btn btn-default pull-right" style="margin-top: 20px;" href="/user">Back</a>
        </div>

        <!-- <?php var_dump(App\Session::all()) ?> -->

        <?php include views_path('inc/messages.php') ?>

    	<form class="form-horizontal" method="post" action="/user/update/<?php echo $user->id ?>">
            <div class="<?php echo App\Flash::has('errors.email') ? 'form-group has-error' : 'form-group' ?>">
                <label class="col-sm-2 control-label">Email</label>
                <div class="col-sm-4">
                    <input class="form-control" name="email" type="text" value="<?php echo (App\Request::old('email')) ? App\Request::old('email') : $user->email ?>" placeholder="type your email address"> 
                    <?php if (App\Flash::has('errors.email')): ?>
                        <span class="help-block"><?php echo App\Flash::get('errors.email') ?></span>
                    <?php endif ?>
                </div>   
            </div>
            <div class="<?php echo App\Flash::has('errors.password') ? 'form-group has-error' : 'form-group' ?>">
                <label class="col-sm-2 control-label">Password</label>
                <div class="col-sm-4">
                    <input class="form-control" name="password" type="password" placeholder="type your password"> 
                    <?php if (App\Flash::has('errors.password')): ?>
                        <span class="help-block"><?php echo App\Flash::get('errors.password') ?></span>
                    <?php endif ?>
                </div> 
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-4">
                    <button class="btn btn-primary" type="submit">Save</button>  
                </div> 
            </div>
        </form>
    </div>

<?php include views_path('inc/footer.php') ?>