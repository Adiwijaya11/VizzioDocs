<!-- Reusable Toast Notification Component -->
<div x-data="notificationSystem()" class="fixed top-4 right-4 z-50 space-y-3 max-w-sm">
    <!-- Notifications list -->
    <template x-for="notification in notifications" :key="notification.id">
        <div x-show="notification.visible"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-x-8"
            x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-x-0"
            x-transition:leave-end="opacity-0 translate-x-8"
            :class="getNotificationClass(notification.type)"
            class="rounded-lg shadow-lg backdrop-blur-sm border p-4 flex items-start gap-3">
            
            <!-- Icon -->
            <div class="flex-shrink-0 mt-0.5">
                <template x-if="notification.type === 'success'">
                    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </template>
                
                <template x-if="notification.type === 'error'">
                    <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </template>
                
                <template x-if="notification.type === 'warning'">
                    <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </template>
                
                <template x-if="notification.type === 'info'">
                    <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                </template>
            </div>
            
            <!-- Content -->
            <div class="flex-1">
                <p class="font-semibold" :class="getTitleClass(notification.type)" x-text="notification.title"></p>
                <p class="text-sm mt-1" :class="getMessageClass(notification.type)" x-text="notification.message"></p>
            </div>
            
            <!-- Close button -->
            <button @click="removeNotification(notification.id)"
                class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>
        </div>
    </template>
</div>

<script>
    function notificationSystem() {
        return {
            notifications: [],
            nextId: 0,
            
            /**
             * Show a notification
             * @param {string} type - 'success', 'error', 'warning', 'info'
             * @param {string} title - Notification title
             * @param {string} message - Notification message
             * @param {number} duration - Auto-close duration in milliseconds (0 = don't auto-close)
             */
            show(type, title, message, duration = 5000) {
                const id = this.nextId++;
                const notification = {
                    id,
                    type,
                    title,
                    message,
                    visible: true
                };
                
                this.notifications.push(notification);
                
                if (duration > 0) {
                    setTimeout(() => this.removeNotification(id), duration);
                }
                
                return id;
            },
            
            /**
             * Remove notification
             */
            removeNotification(id) {
                const notification = this.notifications.find(n => n.id === id);
                if (notification) {
                    notification.visible = false;
                    setTimeout(() => {
                        this.notifications = this.notifications.filter(n => n.id !== id);
                    }, 300);
                }
            },
            
            /**
             * Show success notification
             */
            success(title, message, duration = 5000) {
                return this.show('success', title, message, duration);
            },
            
            /**
             * Show error notification
             */
            error(title, message, duration = 7000) {
                return this.show('error', title, message, duration);
            },
            
            /**
             * Show warning notification
             */
            warning(title, message, duration = 6000) {
                return this.show('warning', title, message, duration);
            },
            
            /**
             * Show info notification
             */
            info(title, message, duration = 5000) {
                return this.show('info', title, message, duration);
            },
            
            /**
             * Get notification background class
             */
            getNotificationClass(type) {
                const classes = {
                    success: 'bg-green-50 border-green-200',
                    error: 'bg-red-50 border-red-200',
                    warning: 'bg-yellow-50 border-yellow-200',
                    info: 'bg-blue-50 border-blue-200'
                };
                return classes[type] || classes.info;
            },
            
            /**
             * Get title text class
             */
            getTitleClass(type) {
                const classes = {
                    success: 'text-green-900',
                    error: 'text-red-900',
                    warning: 'text-yellow-900',
                    info: 'text-blue-900'
                };
                return classes[type] || classes.info;
            },
            
            /**
             * Get message text class
             */
            getMessageClass(type) {
                const classes = {
                    success: 'text-green-700',
                    error: 'text-red-700',
                    warning: 'text-yellow-700',
                    info: 'text-blue-700'
                };
                return classes[type] || classes.info;
            }
        }
    }
</script>

<style>
    /* Ensure animations work smoothly */
    [x-cloak] { display: none; }
</style>
