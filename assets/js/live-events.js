/**
 * MYADS Real-Time Events Engine (SSE Client) — Bootstrap Sample Theme
 * Handles Server-Sent Events (SSE), badge updates, live toasts, and fallback polling.
 */
(function (window, document) {
  'use strict';

  var LiveEventManager = {
    eventSource: null,
    config: {
      enabled: false,
      userId: 0,
      streamUrl: '/live/stream',
      fallbackPollUrl: '/api/notifications/unread-count',
      pollInterval: 30000,
    },
    retryCount: 0,
    maxRetries: 3,
    fallbackTimer: null,
    toastContainer: null,

    init: function (customConfig) {
      if (customConfig && typeof customConfig === 'object') {
        this.config = Object.assign({}, this.config, customConfig);
      } else if (window.MyAdsLiveConfig && typeof window.MyAdsLiveConfig === 'object') {
        this.config = Object.assign({}, this.config, window.MyAdsLiveConfig);
      }

      // Check if user is authenticated
      if (!this.config.userId || this.config.userId <= 0) {
        return;
      }

      this.config.enabled = true;
      this.ensureToastContainer();
      this.connect();
    },

    connect: function () {
      var self = this;

      if (!window.EventSource) {
        console.warn('[LiveEvents] EventSource not supported by browser. Falling back to polling.');
        self.startFallbackPolling();
        return;
      }

      try {
        if (self.eventSource) {
          self.eventSource.close();
        }

        self.eventSource = new EventSource(self.config.streamUrl);

        // 1. Connection Opened
        self.eventSource.onopen = function () {
          self.retryCount = 0;
          if (self.fallbackTimer) {
            clearInterval(self.fallbackTimer);
            self.fallbackTimer = null;
          }
        };

        // 2. Handshake Event
        self.eventSource.addEventListener('handshake', function (e) {
          try {
            var data = JSON.parse(e.data);
            self.handleHandshake(data);
          } catch (err) {
            console.error('[LiveEvents] Error parsing handshake payload:', err);
          }
        });

        // 3. Notifications Event
        self.eventSource.addEventListener('notifications', function (e) {
          try {
            var data = JSON.parse(e.data);
            self.handleNotifications(data);
          } catch (err) {
            console.error('[LiveEvents] Error parsing notifications payload:', err);
          }
        });

        // 4. Messages Event
        self.eventSource.addEventListener('messages', function (e) {
          try {
            var data = JSON.parse(e.data);
            self.handleMessages(data);
          } catch (err) {
            console.error('[LiveEvents] Error parsing messages payload:', err);
          }
        });

        // 5. Feed Event
        self.eventSource.addEventListener('feed', function (e) {
          try {
            var data = JSON.parse(e.data);
            self.handleFeed(data);
          } catch (err) {
            console.error('[LiveEvents] Error parsing feed payload:', err);
          }
        });

        // 6. Admin Alerts Event
        self.eventSource.addEventListener('admin', function (e) {
          try {
            var data = JSON.parse(e.data);
            self.handleAdmin(data);
          } catch (err) {
            console.error('[LiveEvents] Error parsing admin payload:', err);
          }
        });

        // 7. Heartbeat Ping & Reconnect
        self.eventSource.addEventListener('ping', function () {});
        self.eventSource.addEventListener('reconnect', function () {});

        // 8. Error Handling
        self.eventSource.onerror = function () {
          self.retryCount++;
          if (self.retryCount >= self.maxRetries) {
            console.warn('[LiveEvents] Max SSE retries reached. Switching to fallback polling.');
            if (self.eventSource) {
              self.eventSource.close();
              self.eventSource = null;
            }
            self.startFallbackPolling();
          }
        };
      } catch (e) {
        console.error('[LiveEvents] Failed to initialize EventSource:', e);
        self.startFallbackPolling();
      }
    },

    handleHandshake: function (data) {
      if (!data) return;

      if (typeof data.unread_notifications !== 'undefined') {
        this.updateNotificationBadges(data.unread_notifications);
      }
      if (typeof data.unread_messages !== 'undefined') {
        this.updateMessageBadges(data.unread_messages);
      }

      window.dispatchEvent(new CustomEvent('myads:live-handshake', { detail: data }));
    },

    handleNotifications: function (data) {
      if (!data) return;

      if (typeof data.unread_count !== 'undefined') {
        this.updateNotificationBadges(data.unread_count);
      }

      if (data.has_new && data.latest) {
        this.showToast({
          title: 'إشعار جديد',
          body: data.latest.name,
          url: data.latest.url || '/notification',
          icon: data.latest.logo || 'notification',
          type: 'notification',
        });
      }

      window.dispatchEvent(new CustomEvent('myads:live-notification', { detail: data }));
    },

    handleMessages: function (data) {
      if (!data) return;

      if (typeof data.unread_count !== 'undefined') {
        this.updateMessageBadges(data.unread_count);
      }

      if (data.has_new && data.latest) {
        this.showToast({
          title: data.latest.sender_name || 'رسالة جديدة',
          body: data.latest.text_preview || 'أرسل لك رسالة خاصة',
          url: '/messages',
          avatar: data.latest.sender_avatar,
          type: 'message',
        });
      }

      window.dispatchEvent(new CustomEvent('myads:live-message', { detail: data }));
    },

    handleFeed: function (data) {
      if (!data) return;
      window.dispatchEvent(new CustomEvent('myads:live-feed', { detail: data }));
    },

    handleAdmin: function (data) {
      if (!data) return;
      window.dispatchEvent(new CustomEvent('myads:live-admin', { detail: data }));
    },

    updateNotificationBadges: function (count) {
      var safeCount = parseInt(count, 10) || 0;
      var formatted = safeCount > 99 ? '99+' : String(safeCount);

      document.querySelectorAll('[data-notification-badge]').forEach(function (node) {
        if (safeCount > 0) {
          node.hidden = false;
          node.textContent = formatted;
        } else {
          node.hidden = true;
          node.textContent = '';
        }
      });

      document.querySelectorAll('[data-notification-highlight]').forEach(function (node) {
        if (safeCount > 0) {
          node.hidden = false;
          node.textContent = formatted;
        } else {
          node.hidden = true;
          node.textContent = '';
        }
      });
    },

    updateMessageBadges: function (count) {
      var safeCount = parseInt(count, 10) || 0;
      var formatted = safeCount > 99 ? '99+' : String(safeCount);

      document.querySelectorAll('[data-message-unread-count]').forEach(function (node) {
        if (safeCount > 0) {
          node.hidden = false;
          node.textContent = formatted;
        } else {
          node.hidden = true;
          node.textContent = '';
        }
      });

      document.querySelectorAll('[data-message-action-trigger]').forEach(function (node) {
        node.classList.toggle('unread', safeCount > 0);
      });
    },

    ensureToastContainer: function () {
      if (document.getElementById('myads-live-toast-container')) {
        this.toastContainer = document.getElementById('myads-live-toast-container');
        return;
      }

      var container = document.createElement('div');
      container.id = 'myads-live-toast-container';
      container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
      container.style.zIndex = '999999';

      document.body.appendChild(container);
      this.toastContainer = container;
    },

    showToast: function (options) {
      this.ensureToastContainer();
      if (!this.toastContainer) return;

      var toast = document.createElement('div');
      toast.className = 'toast show align-items-center shadow-lg border-0 mb-2';
      toast.setAttribute('role', 'alert');
      toast.setAttribute('aria-live', 'assertive');
      toast.setAttribute('aria-atomic', 'true');
      toast.style.cssText = 'background: var(--bs-body-bg, #fff); color: var(--bs-body-color, #212529); border-radius: 12px;';

      var avatarHtml = '';
      if (options.avatar) {
        avatarHtml = '<img src="' + options.avatar + '" class="rounded-circle me-2 flex-shrink-0" style="width: 36px; height: 36px; object-fit: cover;" alt="">';
      } else {
        avatarHtml = '<div class="rounded-circle me-2 bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px; font-size: 14px;"><i class="fa fa-bell"></i></div>';
      }

      var contentHtml = [
        '<div class="d-flex p-3 align-items-center">',
        avatarHtml,
        '<div class="flex-grow-1 min-w-0">',
        '<a href="' + (options.url || '#') + '" class="text-decoration-none text-reset d-block">',
        '<strong class="d-block text-truncate mb-1" style="font-size: 0.85rem;">' + this.escapeHtml(options.title || '') + '</strong>',
        '<div class="text-truncate text-muted" style="font-size: 0.8rem;">' + this.escapeHtml(options.body || '') + '</div>',
        '</a>',
        '</div>',
        '<button type="button" class="btn-close ms-2" data-bs-dismiss="toast" aria-label="Close"></button>',
        '</div>',
      ].join('');

      toast.innerHTML = contentHtml;

      var closeBtn = toast.querySelector('.btn-close');
      var dismissToast = function () {
        toast.classList.remove('show');
        setTimeout(function () {
          if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
          }
        }, 300);
      };

      if (closeBtn) {
        closeBtn.addEventListener('click', dismissToast);
      }

      this.toastContainer.appendChild(toast);

      // Auto-dismiss after 6 seconds
      setTimeout(dismissToast, 6000);
    },

    escapeHtml: function (str) {
      var div = document.createElement('div');
      div.textContent = str;
      return div.innerHTML;
    },

    startFallbackPolling: function () {
      var self = this;
      if (self.fallbackTimer) return;

      self.fallbackTimer = setInterval(function () {
        if (document.hidden) return;

        fetch(self.config.fallbackPollUrl, {
          headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
          credentials: 'same-origin'
        })
          .then(function (res) { return res.json(); })
          .then(function (data) {
            if (data && typeof data.unread_count !== 'undefined') {
              self.updateNotificationBadges(data.unread_count);
            }
          })
          .catch(function () {});
      }, self.config.pollInterval);
    }
  };

  // Expose globally
  window.LiveEventManager = LiveEventManager;

  // Auto-init if config exists
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () {
      LiveEventManager.init();
    });
  } else {
    LiveEventManager.init();
  }
})(window, document);
