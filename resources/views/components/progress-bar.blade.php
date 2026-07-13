<!-- Reusable Progress Bar Component -->
<div class="w-full" x-data="{ progress: 0, isVisible: true }" x-show="isVisible">
    <!-- Container -->
    <div class="relative w-full bg-gray-100 rounded-full h-3 overflow-hidden shadow-inner">
        <!-- Background gradient -->
        <div class="absolute inset-0 bg-gradient-to-r from-gray-50 to-gray-100"></div>
        
        <!-- Animated progress fill -->
        <div class="relative h-full bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-full transition-all duration-500 ease-out shadow-lg"
            :style="{ width: progress + '%' }"
            :class="{ 'animate-pulse': progress > 0 && progress < 100 }">
            
            <!-- Animated shimmer effect -->
            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent 
                animate-shimmer opacity-0"
                :class="{ 'opacity-100': progress > 0 && progress < 100 }"></div>
            
            <!-- Glow effect -->
            <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-pink-500 blur-lg opacity-50 -z-10"></div>
        </div>
    </div>
    
    <!-- Percentage text (optional) -->
    <div class="mt-2 text-center text-sm font-semibold text-slate-600" x-show="progress > 0">
        <span x-text="progress + '%'"></span>
    </div>
</div>

<!-- Inline Styles for Animations -->
<style>
    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
    
    .animate-shimmer {
        animation: shimmer 2s infinite;
    }
    
    @keyframes spin-slow {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    .animate-spin-slow {
        animation: spin-slow 3s linear infinite;
    }
</style>
