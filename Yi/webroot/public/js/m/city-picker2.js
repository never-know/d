 
/* global $:true */
/* jshint unused:false*/

+ function($) {
  "use strict";

  var defaults;
  var raw = $.cityidata.data;

  var format = function(data) {
	 if(data) return data;
    return [];
	
  };

  var sub = function(data, key) {
	 if(!data.c) {
		var a = {};
		a[key] = {n:""};
		return a;  // 有可能某些县级市没有区
		}
    return data.c;
	
  };

  var getCities = function(d) {
	if(raw[d]) return sub(raw[d], d);
    return [];
	
  };

  var getDistricts = function(p, q) {
	 console.log(p);
	 console.log(q);
	if(raw[p]['c'][q]) return sub(raw[p]['c'][q], q);
    return [];
	
  };
  
 var getObjFirst =function (obj){
	  for(let i in obj) return i;
	}

  $.fn.cityPicker = function(params) {
    params = $.extend({}, defaults, params);
    return this.each(function() {
      var self = this;
      
      var provincesName = $.map(raw, function(d) {
        return d.n;
      });
      var provincesCode = $.map(raw, function(d, id) {
        return id;
      });
      var initCities = sub(raw[provincesCode[0]]);
      var initCitiesName = $.map(initCities,function (c) {
        return c.n;
      });
      var initCitiesCode = $.map(initCities,function (c, id) {
        return id;
      });
      var initDistricts = sub(raw[provincesCode[0]].c[initCitiesCode[0]]);

      var initDistrictsName = $.map(initDistricts,function (c) {
        return c.name;
      });
      var initDistrictsCode = $.map(initDistricts,function (c, id) {
        return id;
      });

      var currentProvince = provincesCode[0];
      var currentCity = initCitiesCode[0];
      var currentDistrict = initDistrictsCode[0];

      var cols = [
          {
            displayValues: provincesName,
            values: provincesCode,
            cssClass: "col-province"
          },
          {
            displayValues: initCitiesName,
            values: initCitiesCode,
            cssClass: "col-city"
          }
        ];

        if(params.showDistrict) cols.push({
          values: initDistrictsCode,
          displayValues: initDistrictsName,
          cssClass: "col-district"
        });

      var config = {

        cssClass: "city-picker",
        rotateEffect: false,  //为了性能
        formatValue: function (p, values, displayValues) {
          return displayValues.join(' ');
        },
        onChange: function (picker, values, displayValues) {
	 
          var newProvince = picker.cols[0].value;
          var newCity;
          if(newProvince !== currentProvince) {
            var newCities = getCities(newProvince);
            //newCity = newCities[0].id;
           
            newCity = getObjFirst(newCities);
            var newDistricts = getDistricts(newProvince, newCity);
            picker.cols[1].replaceValues($.map(newCities, function (c, id) {
              return id;
            }), $.map(newCities, function (c) {
              return c.n;
            }));
            if(params.showDistrict) picker.cols[2].replaceValues($.map(newDistricts, function (d, id) {
              return id;
            }), $.map(newDistricts, function (d) {
              return d.n;
            }));
            currentProvince = newProvince;
            currentCity = newCity;
            picker.updateValue();
            return false; // 因为数据未更新完，所以这里不进行后序的值的处理
          } else {
            if(params.showDistrict) {
              newCity = picker.cols[1].value;
              if(newCity !== currentCity) {
                var districts = getDistricts(newProvince, newCity);
                picker.cols[2].replaceValues($.map(districts, function (d,id) {
                  return id;
                }), $.map(districts, function (d) {
                  return d.n;
                }));
                currentCity = newCity;
                picker.updateValue();
                return false; // 因为数据未更新完，所以这里不进行后序的值的处理
              }
            }
          }
          //如果最后一列是空的，那么取倒数第二列
          var len = (values[values.length-1] ? values.length - 1 : values.length - 2)
          $(self).attr('data-code', values[len]);
          $(self).attr('data-codes', values.join(','));
          if (params.onChange) {
            params.onChange.call(self, picker, values, displayValues);
          }
        },

        cols: cols
      };

      if(!this) return;
      var p = $.extend({}, params, config);
      //计算value
      var val = $(this).val();
	  console.log(val);
      if (!val) val = [130000,130600,130683];
      currentProvince = val[0];
      currentCity = val[1];
      currentDistrict= val[2];
      if(val) {
        p.value = val;
        if(p.value[0]) {
          var cities = getCities(p.value[0]);
          p.cols[1].values = $.map(cities, function (c, id) {
            return id;
          });
          p.cols[1].displayValues = $.map(cities, function (c) {
            return c.name;
          });
        }

        if(p.value[1]) {
          if (params.showDistrict) {
            var dis = getDistricts(p.value[0], p.value[1]);
            p.cols[2].values = $.map(dis,function (d,id) {
              return id;
            });
            p.cols[2].displayValues = $.map(dis, function (d) {
              return d.name;
            });
          }
        } else {
          if (params.showDistrict) {
            var dis = getDistricts(p.value[0], p.cols[1].values[0]);
            p.cols[2].values = $.map(dis,function (d,id) {
              return id;
            });
            p.cols[2].displayValues = $.map(dis,function (d) {
              return d.name;
            });
          }
        }
      }
      $(this).picker(p);
    });
  };

  defaults = $.fn.cityPicker.prototype.defaults = {
    showDistrict: true //是否显示地区选择
  };

}($);
