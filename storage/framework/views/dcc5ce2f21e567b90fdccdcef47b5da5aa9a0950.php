<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.88.1">
    <title>Signin Template · Bootstrap v5.1</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.1/examples/sign-in/">


    <!-- Bootstrap core CSS -->
    <link href="<?php echo e(url('styles/bootstrap.min.css')); ?>" rel="stylesheet">

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>


    <!-- Custom styles for this template -->
    <link rel="stylesheet" type="text/css" href="<?php echo e(url('styles/admin/signin.css')); ?>" />
</head>
<body class="text-center">

<main class="form-signin">
    <form method="POST" action="<?php echo e(route('login_admin')); ?>">
        <?php echo csrf_field(); ?>
        <?php if(Session::has('success')): ?>
            <div class="alert-success"><?php echo e(Session::get('success')); ?>


            </div>
        <?php else: ?>
            <div class="alert-danger"><?php echo e(Session::get('fail')); ?>


            </div>
        <?php endif; ?>
        <img class="mb-4" src="<?php echo e(URL::asset('pics/admin/admin.svg')); ?>" alt="" width="72" height="57">
        <h1 class="h3 mb-3 fw-normal">Please sign in</h1>
        <div class="form-floating">
            <input type="email" class="form-control" placeholder="name@example.com" name="email">
            <label for="floatingInput">Email address</label>
            <span class="text-danger"><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <?php echo e($message); ?> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></span>
        </div>
        <div class="form-floating">
            <input type="password" class="form-control" placeholder="Password" name="password">
            <label for="floatingPassword">Password</label>
            <span class="text-danger"><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <?php echo e($message); ?> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></span>
        </div>

        <div class="checkbox mb-3">
            <label>
                <input type="checkbox" value="remember-me"> Remember me
            </label>
        </div>
        <button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
    </form>
</main>


</body>
</html>
<?php /**PATH D:\Programming Projects\Repositories\railway-managment-system\resources\views/admin/login.blade.php ENDPATH**/ ?>