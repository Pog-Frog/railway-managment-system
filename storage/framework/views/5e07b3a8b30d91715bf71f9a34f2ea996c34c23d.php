<?php
    use Illuminate\Support\Facades\DB;
    $train_types = DB::table('train_types')->get();
?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h4">Insert Train</h1>
    </div>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <form method="POST" action="<?php echo e(route('insert_train')); ?>">
            <?php if(Session::has('success')): ?>
                <div class="alert-success"><?php echo e(Session::get('success')); ?>


                </div>
            <?php else: ?>
                <div class="alert-danger"><?php echo e(Session::get('fail')); ?>


                </div>
            <?php endif; ?>
            <?php echo csrf_field(); ?>
            <div class="row g-3">
                <div class="col-sm-6">
                    <label for="number" class="form-label">Train number<span class="text-muted">(Required)</span></label>
                    <input type="text" class="form-control" id="number" placeholder="" name="number">
                    <span class="text-danger"><?php $__errorArgs = ['number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <?php echo e($message); ?> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></span>
                </div>


                <div class="col-md-7">
                    <label for="type" class="form-label">Type<span class="text-muted">(Required)</span></label>
                    <select class="form-select" id="type" name="type">
                        <?php $__currentLoopData = $train_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tain_type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($tain_type->name); ?>"><?php echo e($tain_type->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <span class="text-danger"><?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <?php echo e($message); ?> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></span>
                </div>


                <div class="col-8">
                    <label for="no_of_cars" class="form-label">Number of cars<span class="text-muted">(Required)</span></label>
                    <input type="type" class="form-control" id="no_of_cars" placeholder="" name="no_of_cars">
                    <span class="text-danger"><?php $__errorArgs = ['no_of_cars'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <?php echo e($message); ?> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></span>
                </div>

                <div style="margin-top: 15px;" class="row ">
                    <label style="margin-left: -10px;" class="form-label">Trian status</label>
                    <div class="form-check col-auto">
                        <input class="form-check-input" type="radio" name="status" id="flexRadioDefault1" checked value="true">
                        <label class="form-check-label" for="flexRadioDefault1">
                            true
                        </label>
                    </div>
                    <div class="form-check col-auto">
                        <input class="form-check-input" type="radio" name="status" id="flexRadioDefault2" value="false">
                        <label class="form-check-label" for="flexRadioDefault2">
                            false
                        </label>
                    </div>
                </div>

            <button class="w-100 btn btn-primary btn-lg" type="submit">Submit</button>
        </form>
    </div>
</main>
<?php /**PATH D:\Programming Projects\Repositories\temp\railway-managment-system\resources\views/admin/insert_train_index.blade.php ENDPATH**/ ?>
