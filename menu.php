<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand logo" href="./" aria-label="MailBox"><?=getLogo() ?></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="./">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="./about">About</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="./price" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Prices
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="./price#1">SmartBox #1</a></li>
            <li><a class="dropdown-item" href="./price#2">SmartBox #2</a></li>
            <li><a class="dropdown-item" href="./price#3">SmartBox #3</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>