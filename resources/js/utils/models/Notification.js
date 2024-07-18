import Model from "./Model";
import Api from "../Api";
import { NOTIFICATION_INDEX_URL } from "@routes/admin";

export default class Notification extends Model {
    constructor(data) {
        super(data);
        this.indexUrl = NOTIFICATION_INDEX_URL;
        this.namePlural = 'notifications';
    }

    markAsRead(notification) {
        return Api.get(`${this.indexUrl}/${notification.id}/mark-as-read`);
    }

    getUrl({ type }) {
        switch (type) {
            case "settings-updated":
                return { name: "settings" };
            case "prescription-received":
                return { name: "prescription-request.index" };
            case "order-feedback-received":
                return { name: "order.feedback" };
            default:
                return {};
        }
    }

    isUnread(notification) {
        return notification.readAt === "";
    }

    isRead(notification) {
        return notification.readAt !== "";
    }
}