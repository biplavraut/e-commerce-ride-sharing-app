<template>
  <li class="dropdown hidden-xs">
    <a href="#"
       class="dropdown-toggle"
       data-toggle="dropdown">
      <i class="material-icons">notifications</i>
      <span class="notification"
            v-if="unreadNotificationsCount>0">{{ unreadNotificationsCount }}</span>
      <p class="hidden-lg hidden-md">
        Notifications
        <b class="caret"></b>
      </p>
    </a>
    <ul class="dropdown-menu notification-container">
      <li v-for="(notification, index) in notifications"
          :key="index"
          v-show="notificationsCount"
          :class="{unread: model.isUnread(notification)}"
          @click="markAsRead(notification)">
        <img :src="notification.from.image"
             class="notification-user-image">
        <span class="nav-notification">
          {{ notification.message }} by
          <i>{{ notification.from.name }}</i><br>
          <small style="color:#AAAAAA">
            <span class="material-icons"
                  style="font-size:12px;">access_time</span>
            <span>{{ notification.createdAt }}</span>
            <span class="material-icons pull-right"
                  v-if="model.isRead(notification)"
                  style="font-size:12px;margin-right:10px;">done_all</span>
          </small>
        </span>
      </li>
      <!--<li v-if="!notificationsCount"
          style="text-align:center;">You don't have any notification
      </li>-->
      <router-link :to="{name: 'notification.index'}"
                   style="text-align:center;"
                   v-if="!notificationsCount"
                   tag="li">You don't have any notification.
      </router-link>
      <router-link :to="{name: 'notification.index'}"
                   style="text-align:center;"
                   v-if="notificationsCount"
                   tag="li">All Notifications
      </router-link>
    </ul>
  </li>
</template>

<script>
  import Notification from "@utils/models/Notification";

  export default {
    name: "Notification",

    data() {
      return {
        notifications: [],
        model        : new Notification()
      };
    },

    methods: {
      async getNotifications() {
        try {
          let data = await this.model.getPaginatedListUncached();
          this.notifications = data.data.filter((notification, index) => {
            if (index < 5) return notification
          });
        } catch (e) {
          console.log(e);
        }
      },

      markAsRead(notification) {
        this.$router.push(this.model.getUrl(notification));

        if (this.model.isRead(notification)) return;

        this.model.markAsRead(notification)
            .then((data) => {
              notification.readAt = data;
            })
            .catch(error => console.log(error));
      },
    },

    computed: {
      notificationsCount() {
        return this.notifications.length;
      },

      unreadNotifications() {
        return this.notifications.filter(this.model.isUnread);
      },

      unreadNotificationsCount() {
        return this.unreadNotifications.length;
      }
    },

    mounted() {
      this.getNotifications();
    }
  };
</script>

<style scoped>
  .notification-container {
    padding : 0;
  }

  .notification-container li {
    width         : 400px;
    padding       : 10px 5px;
    cursor        : pointer;
    border-bottom : 1px solid #BBBBBB;
  }

  .notification-container li:hover {
    background : #EEEEEE !important;
  }

  .nav-notification {
    display      : inline-block;
    padding-left : 55px;
  }

  .nav-notification:hover {
    background : none !important;
    color      : #000000 !important;
    box-shadow : none !important;
  }

  .notification-user-image {
    width         : 45px;
    height        : 45px;
    border-radius : 50%;
    display       : inline-block;
    position      : absolute;
    top           : 50%;
    transform     : translateY(-50%);
  }

  .unread {
    background : rgba(0, 0, 255, 0.1);
  }
</style>
