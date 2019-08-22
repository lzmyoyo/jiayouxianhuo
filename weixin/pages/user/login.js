

const app = getApp();
var lastPage = '';
// pages/user/login.js
Page({

  /**
   * 页面的初始数据
   */
  data: {

  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    lastPage = options.page;
    if (app.globalData.userInfo && app.globalData.userInfo.nickName != '') {
      app.setUserData(app.globalData.userInfo);
    } else {
      // 在没有 open-type=getUserInfo 版本的兼容处理
      wx.getUserInfo({
        success: res => {
          console.log(4444);
          app.globalData.userInfo = res.userInfo;
          app.setUserData(app.globalData.userInfo);
        }
      })
    }
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

  },
  getUserInfo: function (e) {
    app.globalData.userInfo = e.detail.userInfo;
    console.log(e.detail.userInfo);
    app.setUserData(app.globalData.userInfo,function(){
      console.log('回调');
      if (lastPage == undefined || lastPage == '' || lastPage == 'undefined') {
        wx.switchTab({
          url: '/pages/index/index',
        })
      } else {
        wx.switchTab({
          url: lastPage,
        })
      }
    });
  },
  
})