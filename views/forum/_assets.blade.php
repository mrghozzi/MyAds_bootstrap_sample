@once
    @include('theme::store.partials.kb-superdesign-formatter')

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                function enhanceForumCodeBlocks() {
                    if (window.enhanceSuperdesignKbContent) {
                        document.querySelectorAll('.forum-post-paragraph, .forum-content, .activity-post-card, .markdown-content, .post-comment-list, .comment-box, .card-body').forEach(function(el) {
                            window.enhanceSuperdesignKbContent(el);
                        });
                    }
                }
                enhanceForumCodeBlocks();
                setTimeout(enhanceForumCodeBlocks, 500);
                setTimeout(enhanceForumCodeBlocks, 1500);
            });
        </script>
    @endpush
@endonce
