$(function () {
    let favorite = $('.js-favorite-toggle');
    let favoriteTweetId;

    favorite.on('click', function () {
        event.preventDefault();
        event.stopPropagation();
        let $this = $(this);
        favoriteTweetId = $this.data('tweetid');

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: `/tweets/favorite/${favoriteTweetId}`,
            type: 'POST',
            data: {
                'tweetId': favoriteTweetId
            },
        })

            .done(function (data) {
                $this.toggleClass('loved');
                $this.next('.favoritesCount').html(data.tweetFavoritesCount);
            })
    });
});
