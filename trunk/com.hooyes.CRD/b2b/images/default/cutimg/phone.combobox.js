	function PhoneBrandChange(select_phone, value)
	{
		var p_len = select_phone.length;
		for (i=0; i<p_len; i++)
		{
			select_phone.remove(i);
		}
		select_phone.length = 0;
		if (value==0)
		{
			select_phone.options[0]=new Option("-请选择手机型号-", 0)
			idxpp = 1;
			for (idxb in brand_arr)
			{
				for (idxp in brand_phone_arr[idxb])
				{
					elem_text = brand_arr[idxb]+" "+brand_phone_arr[idxb][idxp];
					elem_val = idxp;
					select_phone.options[idxpp]=new Option(elem_text, elem_val)
					idxpp++;
				}
			}
		}
		else
		{
			idxpp = 0;
			for (idxp in brand_phone_arr[value])
			{
				elem_text = brand_phone_arr[value][idxp];
				elem_val = idxp;
				select_phone.options[idxpp]=new Option(elem_text, elem_val)
				idxpp++;
			}
		}
	}

	function BrandInit(brand_select)
	{
		elem_text = "-请选择手机品牌-";
		elem_val = 0;
		brand_select.options[0]=new Option(elem_text, elem_val)
		idxbb = 1;
		for (idx in brand_arr)
		{
			elem_text = brand_arr[idx];
			elem_val = idx;
			brand_select.options[idxbb]=new Option(elem_text, elem_val)
			idxbb++;
		}
		PhoneBrandChange(document.all.PHONE, brand_select.value)
	}

	function PhoneChangeBrand(brand_select, phone_select, phone_id)
	{
		var is_ok = 0;
		for (idxb in brand_arr)
		{
			for (idxp in brand_phone_arr[idxb])
			{
				if (idxp == phone_id)
				{
					is_ok = 1;
					break;
				}
			}
			if (is_ok)
			{
				brand_select.value = idxb;
				PhoneBrandChange(document.all.PHONE, idxb)
				break;
			}
		}
		phone_select.value = phone_id;
	}
