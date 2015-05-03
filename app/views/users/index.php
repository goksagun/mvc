<?php include views_path('inc/header.php') ?>
    <div class="container">
    	<div class="page-header clearfix">
            <h1 class="pull-left">Users</h1>
            <a class="btn btn-default pull-right" style="margin-top: 20px;" href="/user/create">Create new</a>
        </div>

        <?php include views_path('inc/messages.php') ?>

    	<table class="table">
    		<thead>
    			<tr>
    				<th>#</th>
    				<th>email</th>
                    <th>created_at</th>
    				<th></th>
    			</tr>
    		</thead>
    		<tbody>
    			<?php foreach ($users as $user) : ?>
		    		<tr>
		    			<td><?php echo $user->id ?></td>
		    			<td><?php echo $user->email ?></td>
                        <td><?php echo $user->created_at ?></td>
		    			<td class="text-right">
                            <a class="btn btn-info btn-xs" href="/user/edit/<?php echo $user->id ?>"><span class="glyphicon glyphicon-pencil"></span></a>               
                            <a class="btn btn-danger btn-xs" href="/user/delete/<?php echo $user->id ?>"><span class="glyphicon glyphicon-trash"></span></a>               
                        </td>
		    		</tr>
		    	<?php endforeach ?>
    		</tbody>
    	</table>
    </div>

<?php include views_path('inc/footer.php') ?>