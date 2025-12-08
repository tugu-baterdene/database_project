<?php session_start(); ?>
<style>
  :root {
    --uva-navy: #232d4b;
    --uva-orange: #e57200;
  }

  .navbar-uva {
    background: var(--uva-navy) !important;
    border-bottom: 4px solid var(--uva-orange);
  }

  .navbar-brand-uva {
    font-family: 'Georgia', 'Times New Roman', serif;
    font-weight: 600;
    color: white !important;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .navbar-brand-uva svg {
    height: 26px;
    width: 26px;
    fill: var(--uva-orange);
  }

  .nav-link:hover {
    color: var(--uva-orange) !important;
  }
</style>


<header>  
  <nav class="navbar navbar-expand-md navbar-dark navbar-uva">
  <<a class="navbar-brand navbar-brand-uva" href="#">
  <img src="rotunda.svg" alt="UVA Rotunda" height="26" style="margin-left:4px; margin-right:8px;">
  Roommate Connection
</a>

  Roommate Connection
</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar" aria-controls="collapsibleNavbar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="search.php">Search</a>
          </li>
		  <li class="nav-item">
            <a class="nav-link" href="search.php">My Roommates</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="groups.php">My Groups</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="profile.php">My Profile</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</header>    