$(function () {
  $('.lock-background').backstretch(app.base_url + 'assets/img/user/lock-header.jpg');

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
        //console.log(action);
        likecount=parseInt($this.find('span .like_view_count').text());
        if (action == 'like') {
            $.ajax({
                url: app.base_url + 'ajax/books/' + id + '/like',
                type: 'post',
                dataType: 'json',
                success: function (res) {
                    $this.find('.fa')
                        .removeClass('fa-thumbs-up')
                        .addClass('fa-thumbs-down');
                    likecount+=1;
                    if(likecount>1){
                        $this.find('.like_view_count').text(likecount + ' Likes');
                    }
                    else{
                        $this.find('.like_view_count').text(likecount + ' Like');
                    }

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
                    //alert(JSON.stringify(res));
                    $this.find('.fa')
                        .removeClass('fa-thumbs-down')
                        .addClass('fa-thumbs-up');
                    likecount-=1;
                    if(likecount>1){
                        $this.find('.like_view_count').text(likecount + ' Likes');
                    }
                    else{
                        $this.find('.like_view_count').text(likecount + ' Like');
                    }
                    $this.attr('id', 'book-like-' + id)
                        .attr('data-action', 'like');
                },
                error:function(err){

                }

            });

            return false;
        }
    });



    $("#question-form").children('div').steps({
    headerTag: "h3",
    bodyTag: "section",
    transitionEffect: "slideLeft",
    autoFocus: true
  });

  $('.delete').on('hover', function(e) {
    alert('DELETED');
  });

});
