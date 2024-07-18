<template>
  <nav class="navbar theme-color-background navbar-absolute">
    <div class="container-fluid">
      <div class="navbar-minimize">
        <button id="minimizeSidebar"
                class="btn btn-round btn-white btn-fill btn-just-icon">
          <i class="material-icons visible-on-sidebar-regular">more_vert</i>
          <i class="material-icons visible-on-sidebar-mini">view_list</i>
        </button>
      </div>
      <div class="navbar-header">
        <button type="button"
                class="navbar-toggle"
                data-toggle="collapse">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <router-link class="navbar-brand"
                     :to="{name: 'home'}"> {{ settings.name }}</router-link>
      </div>
      <div class="collapse navbar-collapse">
        <ul class="nav navbar-nav navbar-right">
          <notification></notification>
          <li class="dropdown hidden-xs">
            <a href="#"
               class="dropdown-toggle"
               data-toggle="dropdown">
              <i class="material-icons">person</i>
              <p class="hidden-lg hidden-md">
                Notifications
                <b class="caret"></b>
              </p>
            </a>
            <ul class="dropdown-menu">
              <li>
                <router-link :to="{name: 'user.profile'}">My Profile</router-link>
              </li>
              <li>
                <router-link :to="{name: 'user.changePassword'}">Change Password</router-link>
              </li>
              <li>
                <a href="#"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
              </li>
            </ul>
          </li>
          <li class="separator hidden-lg hidden-md hidden-xs"></li>
        </ul>
        <form class="navbar-form navbar-right hidden-xs"
              role="search"
              style="display: none;">
          <div class="form-group form-search is-empty">
            <input type="text"
                   class="form-control"
                   placeholder="Search">
            <span class="material-input"></span>
          </div>
          <button type="submit"
                  class="btn btn-white btn-round btn-just-icon">
            <i class="material-icons">search</i>
            <div class="ripple-container"></div>
          </button>
        </form>
      </div>
    </div>
  </nav>
</template>

<script>
import Common from "./Common";
import Notification from "./Notification";

export default {
	name: "Navbar",

	extends: Common,

	components: {
		Notification
	}
};
</script>
