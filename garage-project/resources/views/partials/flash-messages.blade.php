@if(session('success'))
    <div style="background-color: rgba(16, 185, 129, 0.2); border-left: 4px solid #10b981;" class="p-4 mb-4 rounded-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle" style="color: #10b981;"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm" style="color: #10b981;">
                    {{ session('success') }}
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
            showToast('{{ session('success') }}', 'success');
        });
    </script>
@endif

@if(session('error'))
    <div style="background-color: rgba(239, 68, 68, 0.2); border-left: 4px solid #ef4444;" class="p-4 mb-4 rounded-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle" style="color: #ef4444;"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm" style="color: #ef4444;">
                    {{ session('error') }}
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
            showToast('{{ session('error') }}', 'error');
        });
    </script>
@endif

@if(session('warning'))
    <div style="background-color: rgba(251, 191, 36, 0.2); border-left: 4px solid #fbbf24;" class="p-4 mb-4 rounded-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle" style="color: #fbbf24;"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm" style="color: #fbbf24;">
                    {{ session('warning') }}
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
            showToast('{{ session('warning') }}', 'warning');
        });
    </script>
@endif

@if(session('info'))
    <div class="p-4 mb-4 rounded-lg" style="background-color: rgba(84, 172, 191, 0.15); border-left: 4px solid #54ACBF;">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle" style="color: #54ACBF;"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm" style="color: #a0a0a0;">
                    {{ session('info') }}
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
            showToast('{{ session('info') }}', 'info');
        });
    </script>
@endif

@if($errors->any())
    <div style="background-color: rgba(239, 68, 68, 0.2); border-left: 4px solid #ef4444;" class="p-4 mb-4 rounded-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle" style="color: #ef4444;"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium" style="color: #ef4444;">
                    Il y a {{ $errors->count() }} erreur(s) dans le formulaire :
                </p>
                <ul class="mt-2 text-sm" style="color: #ef4444;" class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
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
            showToast('Il y a {{ $errors->count() }} erreur(s) dans le formulaire', 'error');
        });
    </script>
@endif
