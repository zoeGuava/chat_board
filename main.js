// 渲染留言
function commentRender(username, nickname, comments, id, createdTime) {
  const commentSection = `
    <div class="row">
      <div class="comment_head">
        <div class="comment_title">
          <div class="title_nickname">暱稱</div>
          <div class="title_comments">留言內容</div>
          <div class="title_time">留言時間</div>
        </div>
        <div class="comment_main">
          <div class="nickname">${nickname}</div
          ><div class="comments">${comments}</div
          ><div class="created_time">${createdTime}</div>
        </div>
        <div class="set_btn">
          <a href="update.php?id=${id}" class="btn_edit">編輯</a>
          <a href="delete.php?id=${id}" class="btn_delete" data-comment-id="${id}">刪除</a>
        </div>
        <div class="comment_status">還沒有人回覆喔！</div>
        <form class="comment_reply">
          <div>帳號：${username}</div>
          <div>暱稱：${nickname}</div>
          <div>輸入留言內容：</div>
          <textarea name="comments" rows="5" cols="35" class="edit_comments"></textarea>
          <input type="hidden" name="nickname" value="${nickname}">
          <input type="hidden" name="username" value="${username}">
          <input type="hidden" name="parent_id" value="${id}">
          <input class="send_comment_btn" type="submit" data-comment-id="${id}" value="送出">
        </form>
      </div>
    </div>
  `;
  return commentSection;
}

// 渲染子留言
function commentSubRender(id, nickname, comments, createdTime) {
  const commentSection = `
    <div class="comment_sub" style="background-color:#ffc4c4;">
      <h3>最新留言！</h3>
      <div class="set_btn">
        <a href="update.php?id=${id}" class="btn_edit">編輯</a>
        <a href="delete.php?id=${id}" class="btn_delete" data-comment-id="${id}">刪除</a>
      </div>
      <div>暱稱：${nickname}</div>
      <div>內容：${comments}</div>
      <div>留言時間：${createdTime}</div>
    </div>
  `;
  return commentSection;
}

// 新增主留言
$(document).ready(() => {
  $('.send_comment_btn_main').click((e) => {
    e.preventDefault();
    const sendComments = $('.send_comments').val();
    $.ajax({
      url: './handle_add.php',
      data: {
        username: $('#username').val(),
        nickname: $('#nickname').val(),
        comments: sendComments,
        parent_id: $('#parent_id').val(),
      },
      type: 'POST',
      dataType: 'json',
    }).done((rsp) => {
      // console.log(rsp);
      // 把從 handle_add.php 拿過來的資料用陣列的方式把它選取出來
      const {
        a: username,
        b: nickname,
        c: comments,
        d: id,
        e: createdTime,
      } = {
        a: rsp.username,
        b: rsp.nickname,
        c: rsp.comments,
        d: rsp.id,
        e: rsp.created_time,
      };
      // 將新的留言欄位新增到 HTML 的 DOM 上
      $('.container_list').prepend(commentRender(username, nickname, comments, id, createdTime));
      // 把輸入欄位的值清空
      $('.send_comments').val('');
      // alert('留言成功！');
    }).fail(() => {
      window.alert('留言失敗！');
    });
  });
});

// 新增子留言回覆
$(document).ready(() => {
  $('.container_list').click((e) => {
    e.preventDefault();
    const sendCommentBtn = (e.target.classList.contains('send_comment_btn'));
    if (sendCommentBtn) {
      // console.log('有按到 send_comment_btn');
      // console.log('send_comment_btn 的內容:' + send_comment_btn);

      const sendComments = e.target.form[0].value;
      // console.log('這是要送出去的子留言' + sendComments);

      const parentId = $(e.target).closest('.send_comment_btn').attr('data-comment-id');
      // console.log(e);
      // console.log('parentId 是：' + parentId);

      $.ajax({
        url: './handle_add.php',
        data: {
          username: $('#username').val(),
          nickname: $('#nickname').val(),
          comments: sendComments,
          parent_id: parentId,
        },
        type: 'POST',
        dataType: 'json',
      }).done((rsp) => {
        // console.log(rsp);
        // 把從 handle_add.php 拿過來的資料用陣列的方式把它選取出來
        const {
          a: nickname,
          b: comments,
          c: id,
          d: createdTime,
        } = {
          a: rsp.nickname,
          b: rsp.comments,
          c: rsp.id,
          d: rsp.created_time,
        };

        // 判斷是否為初次留言
        // 不是版主回覆的話 DOM 會少一層（沒有 set_btn）
        const notFirstCommentReply = $(e.target).parent().parent()[0].children[2].classList.contains('accordion');
        // 是版主回覆的話 DOM 會多一層（有 set_btn）
        const notFirstCommentMain = $(e.target).parent().parent()[0].children[3].classList.contains('accordion');
        if (notFirstCommentReply) {
          const targetDOM = $(
            $(e.target).parent().parent()[0].children[2].children[0].lastElementChild,
          );
          targetDOM.append(`${commentSubRender(id, nickname, comments, createdTime)}`);
          // 將新的留言欄位新增到 HTML 的 DOM 上
          // alert('留言成功！');
        } else if (notFirstCommentMain) {
          const targetDOM = $($(e.target).parent().parent()[0].children[3].children[0].children[1]);
          targetDOM.append(`${commentSubRender(id, nickname, comments, createdTime)}`);
          // 將新的留言欄位新增到 HTML 的 DOM 上
          // alert('留言成功！');
        } else {
          const targetDOM = $($(e.target).parent().parent().parent()[0].lastElementChild);
          targetDOM.append(`${commentSubRender(id, nickname, comments, createdTime)}`);
          // 將新的留言欄位新增到 HTML 的 DOM 上
          // alert('留言成功！');
        }
      }).fail(() => {
        window.alert('留言失敗！');
      });
    }
  });
});

// 刪除留言
$(document).ready(() => {
  $('.container').click((e) => {
    const btnDelete = e.target.closest('.btn_delete');
    if (btnDelete) {
      e.preventDefault();
      // console.log('有按到 btnDelete');
      // console.log('btnDelete 的內容:' + btnDelete);

      // 取得留言 id
      const deleteId = $(btnDelete).attr('data-comment-id');
      // console.log('取得留言 id: ' + deleteId);

      $.ajax({
        url: './delete.php',
        data: {
          id: deleteId,
        },
        type: 'POST',
      }).done(() => {
        // alert('留言刪除成功～！');
        // 判斷是否為子留言
        if (e.target.closest('.comment_sub')) {
          $(e.target).parent().parent().hide(400);
        } else {
          $(e.target).parent().parent().parent()
            .hide(400);
        }
      }).fail(() => {
        window.alert('delete 失敗！');
      });
    }
  });
});
