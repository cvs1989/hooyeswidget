if(typeof GoogleMap === 'undefined'){
	var GoogleMap = {};
}
(function(){
	if (!document.getElementById("GG_map")) {
		return false;
	}
	else {
		// 是否可创建Google地图控件
		var isCompatible = new GBrowserIsCompatible();
		if (isCompatible) {
			var mapContainer = document.getElementById("GG_map");
			// 创建GoogleMAP地图实例
			var map = new GMap2(mapContainer);
			//var map = new GMap2(mapContainer, {googleBarOptions: {showOnLoad : true}} ); 
			// 地图默认的比例尺级别
			var perviewLevel = 14; 
			// 大的地图缩放级别控件
			var largeMapControl = new GLargeMapControl(); 
			// 地图缩略图控件
			var overviewMapControl = new GOverviewMapControl(); 
			// 比例尺控件
			var scaleControl = new GScaleControl(); 
			// 地图类形选择控件
			var mapTypeControl = new GMapTypeControl(); 
			// 地址-坐标转换器
			var geocoder = new GClientGeocoder();
			// 上一次的查询地址
			var lastAddress = '';
			// 上一次的查询坐标
			var lastPoint = null;
			// 最后一个创建的标记控件
			var lastMarker = null;
			// 用户标记的最后一个坐标点
			var cusLastPoint = null; 
			
			GoogleMap.mapMsg = [];
			
			// 创建地图
			GoogleMap.Map = function(lat, lng){
				var point = new GLatLng(lat, lng);        
				map.addMapType(G_PHYSICAL_MAP);
				map.setCenter(point, perviewLevel);
				
				map.enableDoubleClickZoom();
				map.enableScrollWheelZoom();
				map.enableContinuousZoom();
				
				map.addControl(largeMapControl)
				map.addControl(overviewMapControl);
				map.addControl(mapTypeControl);
				map.addControl(scaleControl);

			};
			
			// 创建标记
			GoogleMap.createMarker = function(latlng, markerOptions){
				var marker = markerOptions ? new GMarker(latlng, markerOptions) : new GMarker(latlng);
				lastMarker = marker;
				return marker;
			};
			
			// 自定义标记选项
			/* =========================================================================================================================================================================================
			   参数说明：
			   常数：G_DEFAULT_ICON 	标记使用的默认图标。
			   image 	            String 	图标的前景图像 URL。
               shadow 	            String 	图标的阴影图像 URL。
               iconSize 	        GSize 	图标前景图像的像素大小。
               shadowSize 	        GSize 	阴影图像的像素大小。
               iconAnchor 	        GPoint 	此图标在地图上的锚定点相对于图标图像左上角的像素坐标。
               infoWindowAnchor 	GPoint 	信息窗口在此图标上的锚定点相对于图标图像左上角的像素坐标。
               printImage 	        String 	打印地图所用的前景图标图像的 URL。其大小必须与 image 提供的主图标图像的大小相同。
               mozPrintImage 	    String 	用 Firefox/Mozilla 打印地图时所用的前景图标图像的 URL。其大小必须与 image 提供的主图标图像的大小相同。
               printShadow 	        String 	打印地图时所用的阴影图像的 URL。由于大多数浏览器都无法打印 PNG 图像，所以图像格式应该为 GIF。
               transparent 	        String 	在 Internet Explorer 中捕获点击事件时，所用的透明前景图标图像的 URL。此图像应是具有 1% 不透明性的 24 位 PNG 格式的主图标图像，但其大小和形状同主图标相同。
               imageMap             Array of Number  表示图像地图 x/y 坐标的整数数组，用它指定浏览器（非 Internet Explorer）中图标图像的可点击部分。
               maxHeight 	        Integer 指定拖动标记时视觉上垂直“上升”的距离（以像素表示）。（自 2.79 开始）
               dragCrossImage 	    String 	指定拖动图标时十字交叉图像的 URL。（自 2.79 开始）
               dragCrossSize 	    GSize 	指定拖动图标时十字交叉图像的像素大小。（自 2.79 开始）
               dragCrossAnchor 	    GPoint 	指定拖动图标时十字交叉图像的像素坐标偏移量（相对于 iconAnchor）。（自 2.79 开始）
			========================================================================================================================================================================================= */
			GoogleMap.setCustomIcon = function(IconOptions){
				var myIcon = new GIcon(G_DEFAULT_ICON), i;
				for (i in IconOptions) {
					switch (i) {
						case 'iconSize':
						case 'shadowSize':
						case 'dragCrossSize':
							myIcon[i] = new GSize(IconOptions[i][0], IconOptions[i][1]);
							break;
						case 'iconAnchor':	
						case 'infoWindowAnchor':
						case 'infoShadowAnchor':
						case 'dragCrossAnchor':
							myIcon.iconAnchor = new GPoint(IconOptions[i][0], IconOptions[i][1]);
                            break;
						default:
							myIcon[i] = IconOptions[i];
							break;
					}	
					
				}	
				return myIcon;
			};
			// 用户自定义标注
			GoogleMap.customMarkPoint = function(){
				var marker = null;
				var markPoint = cusLastPoint ? new GLatLng(cusLastPoint[0],cusLastPoint[1]) : new GLatLng(lastPoint[0],lastPoint[1]);
				var markOptions = {
					icon: GoogleMap.setCustomIcon({
						image: weburl+'/images/default/ggmap_position.gif'
					}),
					draggable: true
				};
				
				marker = GoogleMap.createMarker(markPoint, markOptions);
				GEvent.addListener(marker, "dragstart", function(){
					map.closeInfoWindow();
				});
				GEvent.addListener(marker, "dragend", function(){
					var custPoint = marker.getPoint();
					var markTip = '<div class="fgmap_markerMsg" id="cusMarkTip">';
					markTip += '<h4>定义经纬度</h4>';
					markTip += '<div id="mapTips" style="font-size:12px;"><p>当前经纬度：(' + custPoint.lat() + ',' + custPoint.lng() + ')<br />';
					markTip += '是否将新位置设置为您要的默认位置？</p>';
					markTip += '<div class="MDB" style="text-align:center;"><button id="MapOK" onclick="GoogleMap.MapOk()"> 确 认 </button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button id="MapCancel" onclick="GoogleMap.MapCancel()"> 取 消 </button></div></div></div>';
					marker.openInfoWindowHtml(markTip);
				});
				map.addOverlay(marker);
			};
			
			// 保存用户自定义坐标
			GoogleMap.MapOk = function(){
				var savedPoint = lastMarker.getPoint();
				var lat = savedPoint.lat(), lng = savedPoint.lng();
				var markTip = document.getElementById('cusMarkTip');
				markTip.innerHTML = '<h4>正在上传您所保存的坐标信息...</h4>';
				
				//AJAX提交坐标
				
				map.clearOverlays();
				var marker = GoogleMap.createMarker(savedPoint);
				if (GoogleMap.mapMsg) {
					GEvent.addListener(marker, "click", function(){
						var msg = '<span class="fgmap_markerMsg">', j;
						msg += '<h4>' + GoogleMap.mapMsg[1][0] + '</h4>';
						for (var j = 1; j < GoogleMap.mapMsg[1].length; j++) {
							msg += GoogleMap.mapMsg[1][j] + "<br />";
						}
						msg += "</span>";
						map.openInfoWindowHtml(savedPoint, msg);
					});
				}
				map.addOverlay(marker);
				map.setCenter(savedPoint);
				window.opener.document.getElementById('mapid').value=lat+','+lng;
				
				/*
				cusLastPoint = [lat,lng];
				window.opener.document.getElementById('shop_x').value=lat;
				
				if(window.opener.document.getElementById('ggmap')){
					window.opener.document.getElementById('ggmap').value="1";
				}
				else{
					window.opener.document.getElementById('shop_z').value="1";
				}
				window.opener.document.getElementById('shop_y').value=lng;
				*/
				alert("保存成功!");
				window.close();
				            
				//lat 纬度 lng 经度
				
				//提交成功
				/*
				function(){
					map.clearOverlays();
					var marker = GoogleMap.createMarker(savedPoint);
					if (GoogleMap.mapMsg) {
						GEvent.addListener(marker, "click", function(){
							var msg = '<span class="fgmap_markerMsg">', j;
							msg += '<h4>' + GoogleMap.mapMsg[1][0] + '</h4>';
							for (var j = 1; j < GoogleMap.mapMsg[1].length; j++) {
								msg += GoogleMap.mapMsg[1][j] + "<br />";
							}
							msg += "</span>";
							map.openInfoWindowHtml(savedPoint, msg);
						});
					}
					map.addOverlay(marker);
					map.setCenter(savedPoint);
				    cusLastPoint = [lat,lng];
				}*/
			};
			
			// 取消用户自定义坐标操作
			GoogleMap.MapCancel = function(){
				map.removeOverlay(lastMarker);
				map.closeInfoWindow();
			};
			
			// 通过地址获得坐标
			GoogleMap.getAddresslatlng = function(response){
				var place = response.Placemark[0];
				var point = new GLatLng(place.Point.coordinates[1], place.Point.coordinates[0]);
				return [place.Point.coordinates[1], place.Point.coordinates[0], point, place];
			};
			
		    // 标注坐标和相应的说明信息
			GoogleMap.MarkerMap = function(lat, lng){
				var marker = null;
				var point = new GLatLng(lat, lng); 
				GoogleMap.Map(lat, lng);
				
				marker = this.createMarker(point);
				if (GoogleMap.mapMsg) {
					GEvent.addListener(marker, "click", function(){
						var msg = '<span class="fgmap_markerMsg">', j;
						msg += '<h4>' + GoogleMap.mapMsg[1][0] + '</h4>';
						for (var j = 1; j < GoogleMap.mapMsg[1].length; j++) {
							msg += GoogleMap.mapMsg[1][j] + "<br />";
						}
						msg += "</span>";
						map.openInfoWindowHtml(point, msg);
					});
				}
				map.addOverlay(marker);
			};
			
			// 将查询地址添加到地图
			GoogleMap.addAddressToMap = function(response){
				map.clearOverlays();
				if (!response || response.Status.code != 200) {
					  //alert("对不起, 我们解析不到该餐厅的经纬度坐标！\n如果您知道该餐厅的准确位置，您可以使用“我要标注”，帮助我们该给该餐厅定位。");
           			 GoogleMap.showLocation(['31.2243531','121.4759159']);
				}
				else {
					var marker = null, point = GoogleMap.getAddresslatlng(response);
					var address = point[3].address, lat = point[0], lng = point[1];

					GoogleMap.mapMsg = (GoogleMap.mapMsg !== '' && (lastAddress === GoogleMap.mapMsg[0])) ? GoogleMap.mapMsg : [address, [point[3].address, ('经度：' + point[1]), ('纬度：' + point[0])]];
					GoogleMap.MarkerMap(lat, lng);
					lastPoint = [lat,lng];
				}
			};
			
			// 将查询坐标添加到地图
			GoogleMap.addPointToMap = function(cPoint){
				map.clearOverlays();
				var marker = null, lat = cPoint[0], lng = cPoint[1];
				GoogleMap.MarkerMap(lat, lng);
				lastPoint = [lat,lng];
			};
			
			// 通过地址/坐标将Marker显示到地图上
			GoogleMap.showLocation = function(cPoint){
				if (typeof cPoint === 'string') {
					geocoder.getLocations(cPoint, this.addAddressToMap);
					lastAddress = cPoint;
				}
				else{
					GoogleMap.addPointToMap(cPoint);
				}
			};
			
			GEvent.addListener(window, 'unload', GUnload);
		}
		else {
			alert("对不起，您的浏览器不支持创建地图！");
		}
	}
})();