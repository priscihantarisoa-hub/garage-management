<?php if(session('success')): ?>
    <div style="background-color: rgba(16, 185, 129, 0.2); border-left: 4px solid #10b981;" class="p-4 mb-4 rounded-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle" style="color: #10b981;"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm" style="color: #10b981;">
                    <?php echo e(session('success')); ?>

                </p>
            </div>
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button onclick="this.closest('[style*='10b981']').remove()" class="inline-flex rounded-md p-1.5 hover:opacity-70" style="background-color: rgba(16, 185, 129, 0.2); color: #10b981;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showToast('<?php echo e(session('success')); ?>', 'success');
        });
    </script>
<?php endif; ?>

<?php if(session('error')): ?>
    <div style="background-color: rgba(239, 68, 68, 0.2); border-left: 4px solid #ef4444;" class="p-4 mb-4 rounded-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle" style="color: #ef4444;"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm" style="color: #ef4444;">
                    <?php echo e(session('error')); ?>

                </p>
            </div>
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button onclick="this.closest('[style*='ef4444']').remove()" class="inline-flex rounded-md p-1.5 hover:opacity-70" style="background-color: rgba(239, 68, 68, 0.2); color: #ef4444;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showToast('<?php echo e(session('error')); ?>', 'error');
        });
    </script>
<?php endif; ?>

<?php if(session('warning')): ?>
    <div style="background-color: rgba(251, 191, 36, 0.2); border-left: 4px solid #fbbf24;" class="p-4 mb-4 rounded-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle" style="color: #fbbf24;"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm" style="color: #fbbf24;">
                    <?php echo e(session('warning')); ?>

                </p>
            </div>
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button onclick="this.closest('[style*='fbbf24']').remove()" class="inline-flex rounded-md p-1.5 hover:opacity-70" style="background-color: rgba(251, 191, 36, 0.2); color: #fbbf24;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showToast('<?php echo e(session('warning')); ?>', 'warning');
        });
    </script>
<?php endif; ?>

<?php if(session('info')): ?>
    <div class="p-4 mb-4 rounded-lg" style="background-color: rgba(84, 172, 191, 0.15); border-left: 4px solid #54ACBF;">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle" style="color: #54ACBF;"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm" style="color: #a0a0a0;">
                    <?php echo e(session('info')); ?>

                </p>
            </div>
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button onclick="this.closest('[style*='54ACBF']').remove()" class="inline-flex rounded-md p-1.5 hover:opacity-70" style="background-color: rgba(84, 172, 191, 0.2); color: #54ACBF;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showToast('<?php echo e(session('info')); ?>', 'info');
        });
    </script>
<?php endif; ?>

<?php if($errors->any()): ?>
    <div style="background-color: rgba(239, 68, 68, 0.2); border-left: 4px solid #ef4444;" class="p-4 mb-4 rounded-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle" style="color: #ef4444;"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium" style="color: #ef4444;">
                    Il y a <?php echo e($errors->count()); ?> erreur(s) dans le formulaire :
                </p>
                <ul class="mt-2 text-sm" style="color: #ef4444;" class="list-disc list-inside">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button onclick="this.closest('[style*='ef4444']').remove()" class="inline-flex rounded-md p-1.5 hover:opacity-70" style="background-color: rgba(239, 68, 68, 0.2); color: #ef4444;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showToast('Il y a <?php echo e($errors->count()); ?> erreur(s) dans le formulaire', 'error');
        });
    </script>
<?php endif; ?>
<?php /**PATH /var/www/resources/views/partials/flash-messages.blade.php ENDPATH**/ ?>