import Dialog from '../../dist/dialog/dialog';
const app = getApp();
// pages/product/add.js
Page({

  /**
   * 页面的初始数据
   */
  data: {
    serverUrl:app.globalData.serverUrl,
    skuKeyOne:'',
    skuKeyTwo: '',
    skuList:[],
    productInfo: { 'productName': '', 'productPrice': '', 'shippingPrice': '','expireDay':''},
    productDesc: [],
    activeNames: [],
    showAddInfo: false,
    addInfoActions: [
      {
        name: '添加图片',
        code: 'image'
      },
      {
        name: '添加文字描述',
        code:'text'
      }
    ]
  },
  onInputProductName:function(e) {
    var productInfo = this.data.productInfo;
    productInfo.productName = e.detail;
    this.setData({
      productInfo:productInfo
    })
  },
  onInputProductPrice: function (e) {
    var productInfo = this.data.productInfo;
    productInfo.productPrice = e.detail;
    this.setData({
      productInfo: productInfo
    })
  },
  onInputProductShippingPrice: function (e) {
    var productInfo = this.data.productInfo;
    productInfo.shippingPrice = e.detail;
    this.setData({
      productInfo: productInfo
    })
  },
  onInputProductExpireDay: function (e) {
    var productInfo = this.data.productInfo;
    productInfo.expireDay = e.detail;
    this.setData({
      productInfo: productInfo
    });
    console.log(productInfo);
  },
  onChange(event) {
    this.setData({
      activeNames: event.detail
    });
  },
  addProduct:function(e){
    
    var sendData = { 'productInfo': this.data.productInfo, 'skuList': this.data.skuList, 'productDesc': this.data.productDesc};

    app.appTokenRequest('/product/add', sendData,function(res) {
      var result = res.data;
      Dialog.alert({
        message: result.message
      }).then(() => {
        if (result.code == 200) {
            console.log('返回');
        } else {
          return false;
        }
      });
    },function(failRes) {
      console.log(failRes);
    });


  },
  addSkuItem:function(e) {
    var skuOneName = this.data.skuKeyOne;
    var skuTwoName = this.data.skuKeyTwo;
    if (skuOneName == '') {
      Dialog.alert({
        message: '请至少填写一个规格的名称。'
      }).then(() => {
        // on close
      });
      return false;
    }
    var skuInfo = { skuOneName: skuOneName, skuOneNameVal: "", skuTwoName: skuTwoName, skuTwoNameVal:'', skuPrice: 10 };
    var allSku = this.data.skuList;
    allSku.push(skuInfo);
    console.log(allSku);
    this.setData({
      skuList: allSku
    });

  },
  inputSkuKey:function(e) {
    var skuKeyVal = e.detail;
    if(e.currentTarget.dataset.key == 'one') {
      this.setData({
        skuKeyOne:skuKeyVal
      });
    }
    if (e.currentTarget.dataset.key == 'two') {
      this.setData({
        skuKeyTwo: skuKeyVal
      });
    }
  },
  inputSkuVal:function(e){
    var skuVal = e.detail;
    var skuIndexNum = e.currentTarget.dataset.index;
    var allSku = this.data.skuList;
    var skuInfo = allSku[skuIndexNum];
    if (e.currentTarget.dataset.key == 'one') {
      skuInfo.skuOneNameVal = skuVal;
    }
    if (e.currentTarget.dataset.key == 'two') {
      skuInfo.skuTwoNameVal = skuVal;
    }
    allSku[skuIndexNum] = skuInfo;
    this.setData({
      skuList:allSku
    });
  },
  removeSku:function(e) {
    var skuIndexNum = e.currentTarget.dataset.index;
    var allSku = this.data.skuList;
    allSku.splice(skuIndexNum, 1);
    console.log(skuIndexNum);
    this.setData({
      skuList: allSku
    });
  },
  addNewInfo:function(e){
    this.setData({
      showAddInfo:true
    });
  },
  onCloseAddInfo:function(e){
    this.setData({
      showAddInfo: false
    });
  },
  onSelectItem:function(e){
    var productDescList = this.data.productDesc;
    var _this = this;
    if (e.detail.code == 'image') {
      var serverUrl = app.globalData.serverUrl;
      wx.chooseImage({
        count: 5,
        sizeType: ['original', 'compressed'],
        sourceType: ['album', 'camera'],
        success(res) {
          // tempFilePath可以作为img标签的src属性显示图片
          const tempFilePaths = res.tempFilePaths;
          for (var i = 0; i < tempFilePaths.length; i++) {
            //上传图片到服务器
            wx.uploadFile({
              url: app.globalData.apiUrl + '/product/uploadFile', //仅为示例，非真实的接口地址
              filePath: tempFilePaths[i],
              name: 'file',
              success(res) {
                var imageItemInfo = { 'imageUrl': '', 'imageText': '', 'code': 'image' };
                var jsonResult = JSON.parse(res.data);
                imageItemInfo.imageUrl = jsonResult.data.imagePath;
                productDescList.push(imageItemInfo);
                console.log(imageItemInfo);
                _this.setData({
                  productDesc: productDescList,
                  showAddInfo: false
                });
              }
            })


            
          }
        }
      })
    }
    if(e.detail.code == 'text') {
      var textItemInfo = { 'textContent': '', 'code': 'text' }
      productDescList.push(textItemInfo);
      this.setData({
        productDesc: productDescList,
        showAddInfo: false
      });
    }
    
  },
  onRemoveItem:function(e) {
    var productDescListIndex = e.target.dataset.index;
    var productDescList = this.data.productDesc;
    productDescList.splice(productDescListIndex, 1);
    this.setData({
      productDesc: productDescList
    });
  },
  inputImageDesc:function(e){
    var imageDescContent = e.detail;
    var imageIndex = e.currentTarget.dataset.index;
    var productDescList = this.data.productDesc;
    var productDescInfo = productDescList[imageIndex];
    productDescInfo.imageText = imageDescContent;
    productDescList[imageIndex] = productDescInfo;
    this.setData({ 
      productDesc: productDescList
    });
    console.log(productDescList);
  },
  inputTextDesc:function(e){
    var textDescContent = e.detail;
    var infoIndex = e.currentTarget.dataset.index;
    var productDescList = this.data.productDesc;
    var productDescInfo = productDescList[infoIndex];
    productDescInfo.textContent = textDescContent;
    productDescList[infoIndex] = productDescInfo;
    this.setData({
      productDesc: productDescList
    });
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {

  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {

  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {

  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {

  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {

  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {

  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  }
})