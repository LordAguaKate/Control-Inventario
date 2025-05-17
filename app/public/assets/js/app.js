const app = {
    routes : {
        home          : '/home',
        login         : "/Session/userAuth",
        prevposts     : '/Posts/getPosts',
        lastpost      : '/Posts/getLastPost',
        openpost      : '/Posts/openPost',
        togglecomments: '/Posts/getComments',
        toggleLike : '/Posts/toggleLike' 
    },
    user : {
        sv : false,
        id : '',
        username : '',
        tipo : ''
    },
    $pp : $("#prev-posts"),
    $lp : $("#content"),
    previousPosts : async function(){
        try{
            let html = `<b>Aún no hay publicaciones</b>`
            this.$pp.html('')

            $posts = await $.getJSON( this.routes.prevposts )
            if( $posts.length > 0 ) { html = ''}
            let primera = true
            $posts.forEach( post => {
                html += `
                    <button onclick="app.openPost(event,${ post.id },this)" 
                            class="list-group-item list-group-item-action ${ primera ? 'active' : '' } pplg">
                        <div class="w-100 border-bottom">
                            <span class="mb-1">${ post.title }</span><br>
                            <small>
                                <i class="bi bi-calendar-week"></i>${ post.fecha }
                            </small>
                        </div>
                        <small>
                            <i class="bi bi-person-circle"></i>${ post.name }
                        </small>
                    </button>`
                    primera = false
            })

            this.$pp.html( html )
        } catch( err ){ console.error( 'Error: ',err)}
    },
    lastPost : async function(){
        try{
            let html = "<h2>Aún no hay publicaciones</h2>"
            this.$lp.html("")

            const $lpost = await $.getJSON( this.routes.lastpost)
            if( $lpost.length > 0 ){
                html = this.mainPostHTMLBuilder( $lpost )
                this.$lp.html(html)
            }
        }catch(err){ console.error( 'Error: ',err)}
    },
    openPost : async function(event,pid,element ){
        event.preventDefault()
        $(".pplg").removeClass('active')
        element.classList.add('active')
        try{
            let html = "<h2>Aún no hay publicaciones</h2>"
            this.$lp.html("")

            const $opost = await $.getJSON( this.routes.openpost + '/' + pid) 
            if( $opost.length > 0 ){
                html = this.mainPostHTMLBuilder( $opost )
                this.$lp.html(html)
            }
        }catch(err){ console.error( 'Error: ',err)}
    },
    mainPostHTMLBuilder : function( post ){
        return `
            <div class="w-100 p-4 shadow rounded bg-body">
                    <h5 class="mb-1">${ post[0].title }</h5>
                <small class="text-muted">
                    <i class="bi bi-calendar-week"></i> ${ post[0].fecha } |
                    <i class="bi bi-person-circle"></i> ${ post[0].name }
                </small>
                <p class="py-3 border-bottom fs-5 lh-sm mb-0" style="text-aling:justify;">
                ${ post[0].body }
                </p>` +
                    ( post[0].image !== null ? 
                        `<div class="my-3 pb-3 border-bottom" style="overflow:hidden;">
                            <img src="" 
                                    alt="Imagen de la publicación" 
                                    class="img-fluid rounded w-100"
                                    style="max-hight:400px; object-fit: contain;">
                        </div>` : `` ) +
                `<a href="#" class="btn btn-link btn-sm text-decoration-none ${ !app.user.cv ? 'disabled' : ''}""
                    onclick="app.toggleLike(event, ${ post[0].id },${ app.user.id})">
                    <i class="bi bi-hand-thumbs-up${ post[1].liked ? '-fill' : ''}" id="iLikeHand"></i> 
                    <span id="likes" class="fw-bold"> ${ post[1].tt } ${ post[1].liked ? '- Te gusta' : ''}"</span>
                </a>
                <span id="comentarios" class="float-end fw-bolder">
                    <small>
                        <a href="#" 
                            id="view-comments" rol="button"
                            class="btn btn-link btn-sm text-decoration-none link-secondary
                                    ${ post[2].tt > 0 ? '' : 'disabled' }"
                            onclick="app.toggleComments(event,${ post[0].id },'#post-comments')"
                            >
                            <i class="bi bi-chat-left-text"></i>
                            <span id="tt-comments">${ post[2].tt }</span> comentarios
                        </a>
                    </small>
                </span>
                <div class="input-group mb-3">
                        <input type="text" name="comment" id="comment"
                                class="form-control rounded-5 bg-body-secondary"
                                disabled readonly
                                placeholder="Regístrate o inicia sesión para poder hacer comentarios">
                        <button class="btn btn-outline-primary rounded-5 border border-light"
                                type="button" id="btn-comment-send"
                                disabled onclick="app.saveComment( ${ post[0].id })">
                                <i class="bi bi-send"></i>
                </div>
                <div class="container mb-2 small-font">
                        <ul class="list-group d-none" id="post-comments">
                        </ul>
                </div>
            </div>
        `
    },

    toggpleLike : function (e, pid, uid){
        e.preventDefault()
        try{
            fetch( this.routes.toggleLike + "/" + pid + "/" +uid)
            .then( resp => resp.json() )
            .then( resp=> {
                $("#likes").html( `${ resp[0].tt} ${ resp[0].liked ?  '- Te gusta ': '' }`)
                $("#ilikeHand").$toggleClass("bi-hand-thumbs-up-fill", resp[0].liked)
                $("#ilikeHand").$toggleClass("bi-hand-thumbs-up-fill", !resp[0].liked)
            }).catch( err => console.error( err ))
        }catch( err ){ console.error( err)}
    },
    toggleComments : async function (e, pid, element){
        if( e ) {
            e.preventDefault()
            $( element ).toggleClass('d-none')
        }else{
            $( element ).removeClass('d-none')
        }
        try{
            const $comments = await $.getJSON( this.routes.togglecomments + '/' + pid )
            let html = ''
            for( let c of $comments ){
                html += `
                    <li class="list-group-item">
                        <p class="mb-0">
                            <span class="fw-bold">${ c.name }</span> | ${ c.fecha }
                        </p>
                        <p class="mb-0">${ c.body }</p>
                    </li>
                `
            }
            $( element ).html( html )
        }catch( err ){ console.error( err )}
    }
}