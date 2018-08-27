<div class="text-center">
  <br />
  <form method="post" action="/play">
    {{ csrf_field() }}
    <button class="btn btn-primary btn-lg" name="hit">Hit</button>
    <button class="btn btn-primary btn-lg" name="stand">Stand</button>
  </form>
</div>
