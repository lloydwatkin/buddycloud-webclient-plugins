unless process.title is 'browser'
    return module.exports =
        name:    "show-client"
        version: "0.1.0"

module.exports = (app, require) ->
    CommentsView = require('../src/views/channel/comments').CommentsView
    createPost = CommentsView.prototype.createPost
    CommentsView.prototype.createPost = (value) ->
      post = createPost.call(this, value)
      post.clientInterface = CommentsView.$('.client-used').val()
      return Post:q


:q
cdc[;


`:q
dvkdvpok 
´