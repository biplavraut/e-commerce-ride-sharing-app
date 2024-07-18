import Model from "./Model";
import { ROAD_BLOCK_NOTIFICATION_URL } from "@routes/admin";

export default class RoadBlockNotification extends Model {
  constructor(data) {
    super(data);
    this.indexUrl = ROAD_BLOCK_NOTIFICATION_URL;
    this.namePlural = "road-block-notifications";
    this.nameLowerCase = "road-block-notification";
  }

}
