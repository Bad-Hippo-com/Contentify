$(document).ready(function () {
    var $comments = $('#comments');
    var $form = $('.create-comment form');
    var $textarea = $form.find('textarea');
    var foreignType = $comments.attr('data-foreign-type');
    var foreignId = $comments.attr('data-foreign-id');
    var editingId = null;
    var $editing = null;
    var busy = false;

    $form.on('submit', function (event) {
        event.preventDefault();
        $form.find('.save').trigger('click');
    });
    $form.find('.save').on('click', function (event) {
        event.preventDefault();
        if (busy) return;
        busy = true;
        $form.find('.save').prop('disabled', true);
        $.ajax({
            url: contentify.baseUrl + (editingId ? 'comments/' + editingId + '/update' : 'comments/store'),
            type: editingId ? 'PUT' : 'POST',
            data: {text: $textarea.val(), foreigntype: foreignType, foreignid: foreignId}
        }).done(function (html) {
            if ($editing) $editing.replaceWith(html);
            else $comments.append(html);
            editingId = null;
            $editing = null;
            $textarea.val('');
        }).fail(contentify.alertRequestFailed).always(function () {
            busy = false;
            $form.find('.save').prop('disabled', false);
        });
    });

    $comments.on('click', '.comment .edit', function (event) {
        event.preventDefault();
        if (busy) return;
        var $article = $(this).closest('.comment');
        $.ajax({url: contentify.baseUrl + 'comments/' + $(this).attr('data-id'), type: 'GET'})
            .done(function (comment) {
                editingId = comment.id;
                $editing = $article;
                $form.show();
                $textarea.val(comment.text).focus();
            }).fail(contentify.alertRequestFailed);
    });

    $comments.on('click', '.comment .quote', function (event) {
        event.preventDefault();
        var creator = $(this).closest('.comment').find('.creator-name').text();
        $.ajax({url: contentify.baseUrl + 'comments/' + $(this).attr('data-id'), type: 'GET'})
            .done(function (comment) {
                $textarea.val($textarea.val() + '[quote' + (creator ? '=' + creator : '') + ']' + comment.text + '[/quote]\n').focus();
            }).fail(contentify.alertRequestFailed);
    });

    $comments.on('click', '.comment .delete', function (event) {
        event.preventDefault();
        if (busy) return;
        var $article = $(this).closest('.comment');
        var id = $(this).attr('data-id');
        $.ajax({url: contentify.baseUrl + 'comments/' + id + '/delete', type: 'DELETE'})
            .done(function () {
                $article.remove();
                if (String(editingId) === String(id)) {
                    editingId = null;
                    $editing = null;
                    $textarea.val('');
                }
            }).fail(contentify.alertRequestFailed);
    });

    $comments.on('click', '.pagination a', function (event) {
        event.preventDefault();
        var page = new URL($(this).attr('href'), window.location.href).searchParams.get('page');
        $.ajax({
            url: contentify.baseUrl + 'comments/paginate/' + foreignType + '/' + foreignId,
            type: 'GET', data: {page: page}
        }).done(function (html) { $comments.html(html); }).fail(contentify.alertRequestFailed);
    });
});
