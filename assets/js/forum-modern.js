(function () {
    "use strict";

    // Forum Markdown Toolbar & Shortcode Injector
    window.ForumEditor = {
        wrap: function (textarea, prefix, suffix, defaultText) {
            if (!textarea) return;
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const sel = textarea.value.substring(start, end) || (defaultText || "");
            const replacement = prefix + sel + suffix;
            textarea.value = textarea.value.substring(0, start) + replacement + textarea.value.substring(end);
            textarea.focus();
            textarea.setSelectionRange(start + prefix.length, start + prefix.length + sel.length);
            textarea.dispatchEvent(new Event("input", { bubbles: true }));
        },

        insert: function (textarea, text) {
            if (!textarea) return;
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            textarea.value = textarea.value.substring(0, start) + text + textarea.value.substring(end);
            textarea.focus();
            textarea.setSelectionRange(start + text.length, start + text.length);
            textarea.dispatchEvent(new Event("input", { bubbles: true }));
        },

        // Fast Client-side Markdown to HTML renderer for instant live preview
        renderMarkdown: function (md) {
            if (!md) return "";
            let html = md
                // Escape HTML tags to prevent XSS
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                // Code blocks ```code```
                .replace(/```([a-z0-9_-]*)\n([\s\S]*?)```/gim, function (match, lang, code) {
                    return '<pre class="bg-dark text-light p-3 rounded-3 my-2"><code class="language-' + (lang || "plaintext") + '">' + code.trim() + "</code></pre>";
                })
                // Inline code `code`
                .replace(/`([^`]+)`/gim, '<code class="bg-light text-primary px-2 py-1 rounded small">$1</code>')
                // Headers # H1, ## H2, ### H3
                .replace(/^### (.*$)/gim, '<h5 class="fw-bold my-2">$1</h5>')
                .replace(/^## (.*$)/gim, '<h4 class="fw-bold my-2">$1</h4>')
                .replace(/^# (.*$)/gim, '<h3 class="fw-bold my-2">$1</h3>')
                // Bold **text**
                .replace(/\*\*(.*?)\*\*/gim, "<strong>$1</strong>")
                // Italic *text*
                .replace(/\*(.*?)\*/gim, "<em>$1</em>")
                // Strikethrough ~~text~~
                .replace(/~~(.*?)~~/gim, "<del>$1</del>")
                // Blockquote > text
                .replace(/^\> (.*$)/gim, '<blockquote class="border-start border-4 border-primary ps-3 my-2 text-muted fst-italic">$1</blockquote>')
                // Links [text](url)
                .replace(/\[([^\]]+)\]\(([^)]+)\)/gim, '<a href="$2" target="_blank" rel="noopener noreferrer" class="text-primary text-decoration-underline">$1</a>')
                // Images ![alt](url)
                .replace(/!\[([^\]]*)\]\(([^)]+)\)/gim, '<img src="$2" alt="$1" class="img-fluid rounded-3 my-2 shadow-sm">')
                // Unordered lists - item
                .replace(/^\s*\-\s(.*$)/gim, "<ul><li>$1</li></ul>")
                // Fix adjoining ul tags
                .replace(/<\/ul>\s*<ul>/gim, "")
                // Line breaks
                .replace(/\n/gim, "<br>");
            return html;
        }
    };

    // Forum Floating Composer Drawer Management (Bootstrap 5 Compatible)
    window.ForumComposer = {
        drawer: null,

        init: function () {
            this.drawer = document.getElementById("forumComposer");
            if (!this.drawer) return;

            const textarea = document.getElementById("forumComposerText");
            const previewBox = document.getElementById("forumComposerPreview");

            if (textarea && previewBox) {
                textarea.addEventListener("input", function () {
                    if (previewBox.classList.contains("is-active")) {
                        previewBox.innerHTML = window.ForumEditor.renderMarkdown(textarea.value);
                    }
                });
            }
        },

        open: function (type, options) {
            if (!this.drawer) this.init();
            if (!this.drawer) return;

            this.drawer.classList.remove("is-minimized");
            this.drawer.classList.add("is-open");

            if (options && options.title) {
                const titleInput = document.getElementById("forumComposerTitle");
                if (titleInput) titleInput.value = options.title;
            }
            if (options && options.catId) {
                const catSelect = document.getElementById("forumComposerCategory");
                if (catSelect) catSelect.value = options.catId;
            }

            const titleInput = document.getElementById("forumComposerTitle");
            if (titleInput && (!options || !options.title)) {
                setTimeout(() => titleInput.focus(), 250);
            }
        },

        close: function () {
            if (!this.drawer) return;
            this.drawer.classList.remove("is-open", "is-fullscreen", "is-minimized");
        },

        minimize: function () {
            if (!this.drawer) return;
            this.drawer.classList.toggle("is-minimized");
        },

        toggleFullscreen: function () {
            if (!this.drawer) return;
            this.drawer.classList.toggle("is-fullscreen");
        },

        togglePreview: function () {
            const textarea = document.getElementById("forumComposerText");
            const previewBox = document.getElementById("forumComposerPreview");
            if (!textarea || !previewBox) return;

            if (previewBox.classList.contains("is-active")) {
                previewBox.classList.remove("is-active");
                previewBox.style.display = "none";
            } else {
                previewBox.innerHTML = window.ForumEditor.renderMarkdown(textarea.value);
                previewBox.classList.add("is-active");
                previewBox.style.display = "block";
            }
        }
    };

    document.addEventListener("DOMContentLoaded", function () {
        window.ForumComposer.init();

        const form = document.getElementById("forumComposerForm");
        if (form) {
            form.addEventListener("submit", function (e) {
                e.preventDefault();
                const submitBtn = document.getElementById("forumComposerSubmitBtn");
                const alertBox = document.getElementById("forumComposerAlert");

                if (alertBox) alertBox.classList.add("d-none");
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i> Submitting...';
                }

                const formData = new FormData(form);

                fetch(form.action, {
                    method: "POST",
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        "Accept": "application/json"
                    },
                    body: formData
                })
                .then(async response => {
                    const data = await response.json().catch(() => ({}));
                    if (!response.ok) {
                        throw new Error(data.message || data.error || "Failed to create discussion");
                    }
                    return data;
                })
                .then(data => {
                    if (data.redirect_url) {
                        window.location.href = data.redirect_url;
                    } else if (data.url) {
                        window.location.href = data.url;
                    } else {
                        window.location.reload();
                    }
                })
                .catch(err => {
                    if (alertBox) {
                        alertBox.textContent = err.message || "An error occurred";
                        alertBox.classList.remove("d-none");
                    } else {
                        alert(err.message || "An error occurred");
                    }
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="fa fa-paper-plane me-1"></i> Post Discussion';
                    }
                });
            });
        }
    });
})();
