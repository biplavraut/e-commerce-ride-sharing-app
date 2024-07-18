<template>
    <div id="print">
        <app-card :title="'Order <b>Detail</b> - STATUS: '+ order.status " body-padding="0">
            <div class="row" style="padding: 10px;">
                <!-- <div class="col-md-12 text-right">
                    <a href="#" class="btn btn-round btn-xs title-right-action btn-success" @click="printOrder()"><i class="material-icons">print</i>Print</a>      
                </div> -->
                <div class="col-md-6 text-left">
                    <h4 class="h4-responsive"><small>Order No.</small><br /><strong><span class="blue-text">#{{ order.orderNo }}</span></strong></h4>
                </div>
                <div class="col-md-6 text-right">
                    <h4 class="h4-responsive"><small>Order Ref.</small><br /><strong><span class="blue-text">#{{ order.refNumber }}</span></strong></h4>
                </div>
            </div>
            <div class="row" style="padding: 10px">
                <!-- Grid column -->
                <div class="col-md-2 text-left medium-text">

                    <p><small>From:</small></p>
                    <p><strong>{{ order.user.firstName +' '+order.user.lastName }}</strong></p>
                    <p>Email: {{ order.user.email }}</p>
                    <p>Phone: {{ order.user.phone }}</p>
                    <p>Order date: {{ formatDate(order.ordered_on) }}</p>
                    <p>Delivery date: {{ formatDate(order.delivery_date) }}</p>
                    <p>Payment Mode: {{ order.payment_mode }}</p>
                </div>
                <!-- Grid column -->

                <!-- Grid column -->
                <div class="col-md-2 text-left medium-text">                    
                    <p><small>Vendor:</small></p>
                    <p><strong>{{ order.vendor.businessName }}</strong></p>
                    <p>Name: {{ order.vendor.firstName+' '+ order.vendor.lastName }}</p>
                    <p>Email: {{ order.vendor.email }}</p>
                    <p>Phone: {{ order.vendor.phone }}</p>

                </div>
                <!-- Grid column -->
                <div class="col-md-3 text-left medium-text">

                    <p><small>Shipping Detail:</small></p>
                    <p><strong>{{ order.location_area }}</strong></p>
                    <p>Location: <a
                            v-bind:href="'/getLocation?lat=' + order.lat + '&lang=' + order.long"
                            target="_blank"
                            title="View on map"
                            >{{ order.location }}</a
                        ></p>
                    <p>Nearest Landmark: {{ order.nearestLandmark ? order.nearestLandmark : "-" }}</p>
                    <p>Alternate Name: {{ order.alyernateName }}</p>
                    <p>Alternate Phone: {{ order.alyernatePhone }}</p>
                    <p>Shipping Applied: {{ order.shipping_charge }}</p>
                </div>
                <div class="col-md-3 text-left medium-text" v-if="order.delivery != null">

                    <p><small>Delivery Rider:</small></p>
                    <p><strong>{{ order.delivery.orderNo ? order.delivery.orderNo : "-" }}</strong></p>
                    <p>Name: {{ (order.delivery.driver.firstName ? order.delivery.driver.firstName: "-") +' '+ (order.delivery.driver.lastName ? order.delivery.driver.lastName : "-") }}</p>
                    <p>Phone: {{ order.delivery.driver.phone ? order.delivery.driver.phone : "-" }}</p>
                    <p>Vehicle Type: {{  order.delivery.driver.vehicleType ? order.delivery.driver.vehicleType : '-' +'  ('+ order.delivery.driver.color ? order.delivery.driver.color : '-' + ')'}}</p>
                    <p>Vehicle No: {{ order.delivery.driver.vehicleNo ? order.delivery.driver.vehicleNo : "-" }}</p>
                    <!-- <p>Shipping Charge: {{ order.additionalDetail.shipping_charge ? order.additionalDetail.shipping_charge : order.shipping_charge+ "(Assigned)" }}</p> -->
                    <p>Shipping Collection: {{ order.shipping_charge > 0 ? order.shipping_charge+ "(Assigned)" : order.additionalDetail.shipping_charge ? order.additionalDetail.shipping_charge : '-'+ "(Unassigned)"  }}</p>
                </div>
                <div class="col-md-3 text-left medium-text" v-else>
                    <p><small>Delivery Rider:</small></p>
                    <p><strong>Not Assigned</strong></p>
                </div>
                <div class="col-md-2 text-left medium-text" style="background::#f8f8f8;">

                    <p><small>Additional Detail:</small></p>
                    <p><strong>Order Ref. Has {{ order.countOrderRef }} Orders</strong></p>
                    <p>Promo Code: {{ order.additionalDetail.coupon_code ? order.additionalDetail.coupon_code : "-" }}</p>
                    <p>Promo Amount: {{ order.additionalDetail.coupon_discount ? order.additionalDetail.coupon_discount : "-" }}</p>
                    <p>gogoReward Redeem: {{ order.additionalDetail.gogo_reward_redeem | commaNumberFormat }} </p>
                    <p>Cashback: {{ order.additionalDetail.order_cashback ? order.additionalDetail.order_cashback : "-" }}</p>
                    <p>Donation: {{ order.additionalDetail.donation ? order.additionalDetail.donation : "-" }}</p>
                    <p>Shipping Charge: {{ order.additionalDetail.shipping_charge | commaNumberFormat }}</p>
                    <p>Amount Total: {{ order.additionalDetail.order_total | commaNumberFormat }}</p>
                    <p>Amount Collected : {{ order.additionalDetail.total_collected | commaNumberFormat }}</p>
                    <p>Amount Refunded: {{ order.additionalDetail.total_refunded | commaNumberFormat  }}</p>
                    <p><strong>Receivable: {{ (order.additionalDetail.order_total ? order.additionalDetail.order_total : 0) - (order.additionalDetail.total_collected ? order.additionalDetail.total_collected : 0)}}</strong></p>
                </div>

            </div>
        </app-card>
        <div class="card">
          <div class="card-body">

            <div class="table-responsive medium-text">
              <table class="table">
                <thead>
                  <tr>
                    <th>Product</th>
                    <th>Size / Color</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Discount</th>
                    <th>Elite Discount</th>
                    <th>Service Charge</th>
                    <th>Tax Amount</th>
                    <th>Selling price</th>
                    <th>Sub Total</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index1) in order.items"
                    :key="index1"
                    :title="'Rs. ' + item.price">
                    <td width="40%">
                        <img
                            :src="item.product.image50"
                            style="width: 50px; height: 50px; border-radius: 50%"
                        />
                        {{ item.name }} / {{ item.product.code }} </td>
                    <td>{{ item.size }} / {{ item.color }}</td>
                    <td>{{ item.quantity }}</td>
                    <td>{{ item.price }}</td>
                    <td>{{ item.discount_type === 'amount' ? item.discount : Math.round(item.price*(item.discount/100)) }}</td>
                    <td>{{ order.user.eliteUser ? item.elitePrice : 0 }}</td>
                    <td>{{ item.serviceChargeAmt }}</td>
                    <td>{{ item.taxAmt }}</td>
                    <td>{{ (item.price + item.serviceChargeAmt + item.taxAmt - (item.discount_type == 'amount' ? item.discount : Math.round(item.price*(item.discount/100))) - (order.user.eliteUser ? item.elitePrice : 0)) | commaNumberFormat}}</td>
                    <td width="10%">Rs. {{ ((item.price + item.serviceChargeAmt + item.taxAmt - (item.discount_type == 'amount' ? item.discount : Math.round(item.price*(item.discount/100))) - (order.user.eliteUser ? item.elitePrice : 0)) * item.quantity) | commaNumberFormat }}</td>
                  </tr>
                  <tr v-if="order.shipping_charge > 0">
                      <td colspan="9" class="text-right">Shipping Charge</td>
                      <td>Rs. {{ order.shipping_charge }}</td>
                  </tr>
                  <tr>
                      <td colspan="9" class="text-right">Total</td>
                      <td>Rs. {{ order.total | commaNumberFormat }}</td>
                  </tr>
                  <tr>
                      <td colspan="9" class="text-right">Refundable Amount</td>
                      <td>Rs. {{ order.refundableAmount | commaNumberFormat }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <app-card v-if="assocOrders.length != 0"
            :title="'Associated Orders'">
        <ul class="list-group-flush list-group">
            <li v-for="order in assocOrders" :key="order.id" class="list-group-item d-flex justify-content-between align-items-center list-group-item-action" waves="" ><!----><!---->
            
            <router-link
              :to="{
                name: 'order.detail',
                params: { id: order.id ,},
              }"
              title="View Detail"
            >
              <span>{{ order.vendor.businessName }} {{order.orderNo }}</span> 
            </router-link>
            <span :class="'badge badge-pill '+ order.status"> {{order.status}}</span>
            <span class="badge badge-pill" >
                Ship. {{ order.shipping_charge }}
            </span>                        
            <span class="badge badge-pill" >
                Rs. {{order.total}}
            </span>              
            </li>    
            <li class="list-group-item d-flex justify-content-between align-items-center list-group-item-action" waves="" v-if="assocOrders.length == 0"> Not Listed</li>              
        </ul>
        </app-card>
        <app-card v-if="orderFeedbacks.length != 0"
            :title="'Order Feedback'">
            <div v-for="feedback in orderFeedbacks" :key="feedback.id">
            <div class="media">
                <div class="media-left">
                    <img :src="order.user.image" class="media-object" style="width:40px">
                </div>
                <div class="media-body">
                    <h4 class="media-heading title">{{ order.user.firstName +' '+order.user.lastName }}</h4>
                    <p class="komen">
                        {{ feedback.feedback }}<br>
                        <small>On: {{ feedback.createdAt }}</small>
                    </p>
                </div>
            </div>

            <div class="geser" v-if="feedback.respond != null">
                <div class="media">
                    <div class="media-left">
                    <img :src="feedback.admin != null ? feedback.admin.image : ''" class="media-object" style="width:40px">
                    </div>
                    <div class="media-body">
                    <h4 class="media-heading title">{{ feedback.admin != null ? feedback.admin.name : ""}}</h4>
                    <p class="komen">
                        {{ feedback.respond }}<br>
                        <small>On. {{ feedback.updatedAt }}</small>
                    </p>
                    </div>
                </div>
            </div>
            </div>
        </app-card>
    </div>
</template>
<script>
export default {
    data() {
        return {
            order: {items: [], delivery:{ driver: {}} ,user: {}, vendor: {}, additionalDetail:[] },
            assocOrders: {},
            orderFeedbacks:{ admin: {}, user: {}}
        }
    },
    methods: {
        formatDate(date) {
            if(date != "-")
                return moment(date).format("LLLL");
            else
                return "Not Available";
        },
        getData(){
            axios.get('/admin/order-detail/'+this.$route.params.id).then((response) => {
                this.order = response.data.data;
                this.assocOrders = response.data.assocOrders;
                this.orderFeedbacks = response.data.data.orderFeedback;
            });
        },
        printOrder(){
            alert('I am Print');
        }
    },    
    mounted() {
        if (this.$route.params.hasOwnProperty("id")) {
            this.getData(); 
        }
        if(this.order.delivery == null){
            this.order.delivery = { driver: {}};
        }
    },
    created(){
        
    }
}
</script>
<style>
    .medium-text{
        font-size: 13px;
        font-weight: 400;
    }
    .blue-text{
        font-size: 16px;
    }
    .CANCELLED{
        background: red;
    }
    .PENDING{
        background: orange;
    }
    .title {
    font-size: 14px;
    font-weight:bold;
    }
    .komen {
        font-size:14px;
    }
    .geser {
        margin-left:55px;
        margin-top:5px;
    }

</style>