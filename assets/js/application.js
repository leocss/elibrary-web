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


    //########################################-book-unlike-like############################################################
    /**
     * Register a click event on the like
     * and unlike buttons in article pages...
     */
    $('.book-unlike-like').on('click', function (e) {

        e.preventDefault();

        var $this = $(this),
            action = $this.attr('data-action'),
            json_data,
            id = $this.attr('id')
                .replace('book-like-', '')
                .replace('book-unlike-', '');

        if (action == 'like') {
            $.ajax({
                url: app.base_url + 'ajax/books/' + id + '/like',
                type: 'post',
                dataType: 'json',
                success: function (res) {
                    alert("like");
                    $this.find('.glyphicon')
                        .removeClass('glyphicon-thumbs-up')
                        .addClass('glyphicon-thumbs-down');

                    $this.attr('id', 'book-unlike-' + id)
                        .attr('data-action', 'unlike');

                }
            });
        } else if (action == 'unlike') {
            $.ajax({
                url: app.base_url + 'ajax/books/' + id + '/unlike',
                type: 'post',
                dataType: 'json',
                success: function (res) {
                    alert("unlike");
                    $this.find('.glyphicon')
                        .removeClass('glyphicon-thumbs-down')
                        .addClass('glyphicon-thumbs-up');

                    $this.attr('id', 'book-like-' + id)
                        .attr('data-action', 'like');
                }
            });

            return false;
        }
    })

<<<<<<< HEAD
    //########################################-book-unlike-like############################################################
=======
      return false;
    }
  });


    $("#question-form").children('div').steps({
        headerTag: "h3",
        bodyTag: "section",
        transitionEffect: "slideLeft",
        autoFocus: true
    });


>>>>>>> b7e3d8aa957d6401129f87622e639e763d1c0c8e

});
