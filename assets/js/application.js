$(function () {

  /**
   * Register a click event on the like
   * and unlike buttons in article pages...
   */
  $('.article-unlike-like').on('click', function (e) {

    e.preventDefault();

    var $this = $(this),
      action = $this.attr('data-action'),
      json_data,
      id = $this.attr('id')
        .replace('article-like-', '')
        .replace('article-unlike-', '');

    if (action == 'like') {
      $.ajax({
        url: app.base_url + 'ajax/articles/' + id + '/like',
        type: 'post',
        dataType: 'json',
        success: function (res) {
          $this.html('Unlike')
            .attr('id', 'article-unlike-' + id)
            .attr('data-action', 'unlike');
        }
      });
    } else if (action == 'unlike') {
      $.ajax({
        url: app.base_url + 'ajax/articles/' + id + '/unlike',
        type: 'post',
        dataType: 'json',
        success: function (res) {
          $this.html('Like')
            .attr('id', 'article-like-' + id)
            .attr('data-action', 'like');
        }
      });

      return false;
    }
  })

});
