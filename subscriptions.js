'use strict'
{

  const hidden = document.getElementById('hidden');
  const test = document.getElementById('test');
  const form = document.querySelector('form');
  const mask = document.getElementById('mask');
  const sum = document.getElementById('sum');
  const count = document.getElementById('count');
  const avg = document.getElementById('avg');

  const addBtn = document.getElementById('addBtn');
  addBtn.addEventListener('click', ()=> {
    form.classList.remove('hidden');
    mask.classList.remove('hidden');
    document.getElementById('new_title').focus();
  });

  let deleteLabel;
  const deleteBtn = document.getElementById('deleteBtn');
  deleteBtn.addEventListener('click', (e)=> {
    e.stopPropagation();
    if (deleteLabel) {
      closeDeleteArea();
      return;
    }
    openDeleteArea();
  });

  window.addEventListener('click', ()=> {
    closeDeleteArea();
  });

  function openDeleteArea() {
    const subsc = document.querySelectorAll('.subsc');
    subsc.forEach(e => {
      e.classList.add('edit');
    });
    setTimeout(() => {
      const xxx = document.querySelectorAll('.xxx');
      xxx.forEach(e => {
      e.classList.remove('hidden');
    })}, 300);
    deleteLabel = true;
  }

  function closeDeleteArea() {
    const xxx = document.querySelectorAll('.xxx');
    xxx.forEach(e => {
      e.classList.add('hidden');
      const subsc = document.querySelectorAll('.subsc');
      subsc.forEach(e => {
        e.classList.remove('edit');
      });
    });
    deleteLabel = false;
  }


  mask.addEventListener('click', ()=> {
    mask.classList.add('hidden');
    form.classList.add('hidden');
  });


  $(function() {
    //add
    $('#new_subsc_form').on('submit', function() {
      const title = $('#new_title').val();
      const price = $('#new_price').val();
      // ajax処理
      $.post('_ajax.php', {
        title: title,
        price: price,
        mode: 'create',
        token: $('#token').val()
      }, function(res) {
        const $li = $('#template').clone();
        $li
          .attr('id', 'subsc_' + res.id)
          .data('id', res.id)
          .find('.title').text(title)
          .end()
          .find('.price').text('¥ ' + price.replace(/(\d)(?=(\d\d\d)+$)/g, '$1,'));
        $('#lists').prepend($li.css('display', 'none').fadeIn(500));
        $('#new_title').val('').focus();
        $('#sum').html(res.sum);
        $('#count').html(res.count);
        $('#avg').html(res.avg);
      });
      return false;
    });

    //delete
    $('#lists').on('click', '.xxx', function() {
    const id = $(this).parents('li').data('id');
    // ajax処理
    if (confirm('are you sure?')) {
      $.post('_ajax.php', {
        id: id,
        mode: 'delete',
        token: $('#token').val()
      }, function(res) {
        $('#subsc_' + id).fadeOut(500);
        $('#sum').html(res.sum);
        $('#count').html(res.count);
        $('#avg').html(res.avg);
      });
    }
  });

  });


}
