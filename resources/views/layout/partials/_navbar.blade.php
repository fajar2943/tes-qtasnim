<header class="pb-3 mb-4 border-bottom navbar navbar-expand-lg navbar-light">
    <a class="navbar-brand" href="#">Qtasnim</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="mx-auto">
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item mx-4">
              <a class="nav-link @if(request()->is('/')) active @endif" href="/">Home</a>
            </li>
            <li class="nav-item mx-4">
              <a class="nav-link @if(request()->is('transactions')) active @endif" href="/transactions">Transaction</a>
            </li>
            <li class="nav-item mx-4">
              <a class="nav-link @if(request()->is('categories')) active @endif" href="/categories">Category</a>
            </li>
            <li class="nav-item mx-4">
              <a class="nav-link @if(request()->is('products')) active @endif" href="/products">Product</a>
            </li>
          </ul>
        </div>
    </div>
</header>
