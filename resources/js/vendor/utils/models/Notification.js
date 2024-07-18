import Model from "./Model";
import Api from "../Api";
import {NOTIFICATION_INDEX_URL} from "@routes/admin";

export default class Notification extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = NOTIFICATION_INDEX_URL;
    this.namePlural = 'notifications';
  }

  markAsRead(notification) {
    return Api.get(`${this.indexUrl}/${notification.id}/mark-as-read`);
  }

  getUrl({type}) {
    switch (type) {
      case "settings-updated":
        return {name: "settings"};
      default:
        return {};
    }
  }

  isUnread(notification) {
    return notification.readAt === null;
  }

  isRead(notification) {
    return notification.readAt !== null;
  }
}
