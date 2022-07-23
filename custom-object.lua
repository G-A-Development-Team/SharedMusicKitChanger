--------------------------------------------
--          CUSTOM OBJECT CReATION        --
-- Credit To: Chicken4676                 --
--------------------------------------------
function gui._Custom(ref, varname, name, x, y, w, h, paint, custom_vars)
	local tbl = {val = 0}

	local function read(v)
		tbl.val = v
	end

	local function write()
		return tbl.val
	end
	
	local function is_in_rect(x, y, x1, y1, x2, y2)
		return x >= x1 and x < x2 and y >= y1 and y < y2;
	end
	
	local GuiObject = {
		element = nil,
		custom_vars = custom_vars or {},
		name = name,
		
		_element_pos_x = x,
		_element_pos_y = y,
		
		_element_width = w,
		_element_height = h,
		
		
		
		_parent = ref,
			
		GetValue = function(self)
			return self.element:GetValue()
		end,
		
		SetValue = function(self, value)
			return self.element:SetValue(value)
		end,
		
		GetName = function(self)
			return self.name
		end,
		
		SetName = function(self, name)
			self.name = name
		end,
		
		SetPosX = function(self, x)
			self.element:SetPosX(x)
			self._element_pos_x = x
		end,
		
		SetPosY = function(self, y)
			self.element:SetPosY(y)
			self._element_pos_y = y
		end,
		
		SetPos = function(self, x, y)
			self.element:SetPosX(x)
			self.element:SetPosY(y)
			self._element_pos_x = x
			self._element_pos_y = y
		end,
		
		GetPos = function(self)
			return self._element_pos_x, self._element_pos_y
		end,
		
		SetWidth = function(self, width)
			self.element:SetWidth(width)
			self._element_width = width
		end,
		
		SetHeight = function(self, height)
			self.element:SetHeight(height)
			self._element_height = height
		end,
		
		SetSize = function(self, w, h)
			self.element:SetWidth(w)
			self.element:SetHeight(h)
			self._element_width = width
			self._element_height = height
		end,
		
		GetSize = function(self)
			return self._element_width, self._element_height 
		end,
		
		SetVisible = function(self, b)
			self.element:SetInvisible(not b)
		end,
		
		SetInvisible = function(self, b)
			self.element:SetInvisible(b)
		end,
		
		GetParent = function(self)
			return self._parent
		end,
		
		_mouse_left_released = true,
		_old_mouse_left_released = true,
		
		OnClick = function(self) -- you rewrite this function when creating elements
			
		end,
				
		hovering = function(x, y, x2, y2)
			local mx, my = input.GetMousePos()
			return is_in_rect(mx, my, x, y, x2, y2)
		end,
		
		_mouse_hovering = false,
		_old_mouse_hovering = false,
		OnHovered = function(self)
			
		end,	
	}
	
	local meta = {__index = custom_vars}
	setmetatable(GuiObject, meta)
	
	local function _paint(x, y, x2, y2, active)
	
		local mx, my = input.GetMousePos()
		local hovering = GuiObject.hovering(x, y, x2, y2)
		
		if hovering then
			GuiObject._mouse_hovering = true		
			if input.IsButtonReleased(1) then
				GuiObject._mouse_left_released = true
			end
		
			if input.IsButtonDown(1) then
				GuiObject._mouse_left_released = false
			end
		
			if GuiObject._mouse_left_released ~= GuiObject._old_mouse_left_released then
				if not GuiObject._mouse_left_released then -- Clicked
					GuiObject:OnClick()
				end
				GuiObject._old_mouse_left_released = GuiObject._mouse_left_released
			end
		else
			GuiObject._mouse_hovering = false
		end

		if GuiObject._old_mouse_hovering ~= GuiObject._mouse_hovering then
			-- print(GuiObject._mouse_hovering)
			GuiObject:OnHovered(GuiObject._mouse_hovering)
			GuiObject._old_mouse_hovering = GuiObject._mouse_hovering
		end
		
		local width = x2 - x
		local height = y2 - y
		paint(x, y, x2, y2, active, GuiObject, width, height)
	end
	
	local custom = gui.Custom(ref, varname, x, y, w, h, _paint, write, read)
	GuiObject.element = custom
	
	return GuiObject
end
