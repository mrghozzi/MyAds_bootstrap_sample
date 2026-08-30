<script>
    document.addEventListener('DOMContentLoaded', function () {
        const fallbackCopy = function (text) {
            const helper = document.createElement('textarea');
            helper.value = text;
            helper.setAttribute('readonly', 'readonly');
            helper.style.position = 'absolute';
            helper.style.left = '-9999px';
            document.body.appendChild(helper);
            helper.select();
            document.execCommand('copy');
            document.body.removeChild(helper);
        };

        document.querySelectorAll('.js-dev-copy').forEach(function (button) {
            button.addEventListener('click', async function () {
                const directValue = button.getAttribute('data-copy');
                const targetSelector = button.getAttribute('data-copy-target');
                const target = targetSelector ? document.querySelector(targetSelector) : null;
                const value = directValue || (target ? target.value : '');

                if (!value) {
                    return;
                }

                try {
                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        await navigator.clipboard.writeText(value);
                    } else {
                        fallbackCopy(value);
                    }

                    button.dataset.copied = 'true';
                    window.clearTimeout(button.__developerCopyTimer);
                    button.__developerCopyTimer = window.setTimeout(function () {
                        button.dataset.copied = 'false';
                    }, 1400);
                } catch (error) {
                    fallbackCopy(value);
                }
            });
        });

        document.querySelectorAll('.js-dev-toggle-secret').forEach(function (button) {
            button.addEventListener('click', function () {
                const selector = button.getAttribute('data-target');
                const target = selector ? document.querySelector(selector) : null;
                const icon = button.querySelector('i');

                if (!target) {
                    return;
                }

                const reveal = target.type === 'password';
                target.type = reveal ? 'text' : 'password';

                if (icon) {
                    icon.classList.toggle('fa-eye', !reveal);
                    icon.classList.toggle('fa-eye-slash', reveal);
                }
            });
        });

        const updateForm = document.getElementById('dev-update-app-form');
        if (updateForm) {
            updateForm.addEventListener('submit', async function (e) {
                e.preventDefault();
                const updateBtn = document.getElementById('dev-update-btn');
                const originalText = updateBtn ? updateBtn.innerHTML : '';
                if (updateBtn) {
                    updateBtn.disabled = true;
                    updateBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> {{ __("messages.saving") ?? "Saving..." }}';
                }

                const alertContainer = document.getElementById('dev-form-alert');
                const successContainer = document.getElementById('dev-form-success');
                if (alertContainer) alertContainer.style.display = 'none';
                if (successContainer) successContainer.style.display = 'none';

                try {
                    const formData = new FormData(updateForm);
                    const response = await fetch(updateForm.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const data = await response.json().catch(() => ({}));

                    if (response.ok && data.success) {
                        if (successContainer) {
                            successContainer.innerHTML = '<strong>' + (data.message || 'Saved successfully!') + '</strong>';
                            successContainer.style.display = 'block';
                            successContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        } else {
                            alert(data.message || 'Saved successfully!');
                        }
                        return;
                    }

                    let errorMsg = data.message || 'Error occurred while saving.';
                    if (data.errors) {
                        const list = Object.values(data.errors).flat().join('<br>');
                        if (list) errorMsg = list;
                    }

                    if (alertContainer) {
                        alertContainer.innerHTML = '<strong>' + errorMsg + '</strong>';
                        alertContainer.style.display = 'block';
                        alertContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    } else {
                        alert(errorMsg);
                    }
                } catch (err) {
                    console.error('AJAX Update Error, falling back to standard submit:', err);
                    updateForm.submit();
                } finally {
                    if (updateBtn) {
                        updateBtn.disabled = false;
                        updateBtn.innerHTML = originalText;
                    }
                }
            });
        }
    });
</script>
