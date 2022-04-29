<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h4">Insert Train Type</h1>
    </div>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">

        <form method="POST" action="<?php echo e(route('insert_train_type')); ?>">
            <?php if(Session::has('success')): ?>
                <div class="alert-success"><?php echo e(Session::get('success')); ?>


                </div>
            <?php else: ?>
                <div class="alert-danger"><?php echo e(Session::get('fail')); ?>


                </div>
            <?php endif; ?>
            <?php echo csrf_field(); ?>
            <div class="row g-3">
                <div class="col-10">
                    <label for="name" class="form-label">Train number<span class="text-muted">(Required)</span></label>
                    <input type="text" class="form-control" id="name" placeholder="" name="name">
                    <span class="text-danger"><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <?php echo e($message); ?> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></span>
                </div>
                <button class="w-100 btn btn-primary btn-lg" type="submit">Submit</button>
        </form>
    </div>
</main>
<?php /**PATH D:\Programming Projects\Repositories\temp\railway-managment-system\resources\views/admin/insert_train_type.blade.php ENDPATH**/ ?>