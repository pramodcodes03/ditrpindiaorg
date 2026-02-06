<?php
include_once('database_results.class.php');
include_once('access.class.php');

class metadata extends access
{
	
	public function list_category()
	{
		$output = '<thead>
						<th>#</th>
						<th>Category Name</th>
						<th>Action</th>
					</thead><tbody>';
		$sql = "SELECT * FROM ad_category_master";
		$res = parent::execQuery($sql);
		if($res && $res->num_rows>0)
		{
			$srNo=1;
			while($data = $res->fetch_assoc())
			{
				$AD_CATEGORY_ID 	= $data['AD_CATEGORY_ID'];
				$AD_CATEGORY	 	= $data['AD_CATEGORY'];
				$AD_CATEGORY_ACTIVE = $data['AD_CATEGORY_ACTIVE'];
				$CREATED_BY 		= $data['CREATED_BY'];
				$CREATED_ON 		= $data['CREATED_ON'];
				$UPDATED_BY 		= $data['UPDATED_BY'];
				$UPDATED_ON 		= $data['UPDATED_ON'];
				
				$output .='<tr>';
				$output .= '<td>'.$srNo.'</td>';
				$output .= '<td>'.$AD_CATEGORY.'</td>';
				$output .= '<td><a href="#" class="btn btn-xs btn-primary" onclick=""><i class="fa fa-pencil fa-1x"></i>
</a>
									<a href="#" class="btn btn-xs btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i>
</a></td>';
				$output .='</tr>';
				
				$srNo++;
			}
		}
		return $output .= '</tbody>';
	}
	public function list_subcategory()
	{
		$output = '<thead>
						<th>#</th>
						<th>Sub-category Name</th>
						<th>Action</th>
					</thead><tbody>';
		$sql = "SELECT * FROM ad_subcategory_master";
		$res = parent::execQuery($sql);
		if($res && $res->num_rows>0)
		{
			$srNo=1;
			while($data = $res->fetch_assoc())
			{
				$AD_SUBCATEGORY_ID 	= $data['AD_SUBCATEGORY_ID'];
				$AD_CATEGORY_ID	 	= $data['AD_CATEGORY_ID'];
				$AD_SUBCATEGORY = $data['AD_SUBCATEGORY'];
				$CREATED_BY 		= $data['CREATED_BY'];
				$CREATED_ON 		= $data['CREATED_ON'];
				$UPDATED_BY 		= $data['UPDATED_BY'];
				$UPDATED_ON 		= $data['UPDATED_ON'];
				
				$output .='<tr>';
				$output .= '<td>'.$srNo.'</td>';
				$output .= '<td>'.$AD_SUBCATEGORY.'</td>';
				$output .= '<td><a href="#" class="btn btn-xs btn-primary" onclick=""><i class="fa fa-pencil fa-1x"></i>
</a>
									<a href="#" class="btn btn-xs btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i>
</a></td>';
				$output .='</tr>';
				
				$srNo++;
			}
		}
		return $output .= '</tbody>';
	}
	public function list_item_type()
	{
		$output='<thead>
					<th>#</th>
					<th>Type</th>
					<th>Category</th>
					<th>Subcategory</th>
					<th>Action</th>
				<thead><tbody>';
		$sql = "SELECT A.*,B.AD_CATEGORY AS AD_CATEGORY_NAME,C.AD_SUBCATEGORY AS AD_SUBCATEGORY_NAME FROM ad_category_items_master A LEFT JOIN ad_category_master B ON A.CATEGORY_ID=B.AD_CATEGORY_ID LEFT JOIN ad_subcategory_master C ON A.SUBCATEGORY_ID=C.AD_SUBCATEGORY_ID";
		$res = parent::execQuery($sql);
		if($res && $res->num_rows>0)
		{
			$srNo=1;
			while($data = $res->fetch_assoc())
			{
				$AD_CATEGORY_ITEM_ID= $data['AD_CATEGORY_ITEM_ID'];
				$SUBCATEGORY_ID	 	= $data['SUBCATEGORY_ID'];
				$CATEGORY_ID 		= $data['CATEGORY_ID'];
				$AD_CATEGORY_NAME 		= $data['AD_CATEGORY_NAME'];
				$AD_SUBCATEGORY_NAME 		= $data['AD_SUBCATEGORY_NAME'];
				$ITEM_NAME	 		= $data['ITEM_NAME'];
				$CREATED_BY 		= $data['CREATED_BY'];
				$CREATED_ON 		= $data['CREATED_ON'];
				$UPDATED_BY 		= $data['UPDATED_BY'];
				$UPDATED_ON 		= $data['UPDATED_ON'];
				
				$output .='<tr>';
				$output .= '<td>'.$srNo.'</td>';
				$output .= '<td>'.$ITEM_NAME.'</td>';
				$output .= '<td>'.$AD_CATEGORY_NAME.'</td>';
				$output .= '<td>'.$AD_SUBCATEGORY_NAME.'</td>';
				$output .= '<td><a href="#" class="btn btn-xs btn-primary" onclick=""><i class="fa fa-pencil fa-1x"></i>
</a>
									<a href="#" class="btn btn-xs btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i>
</a></td>';
				$output .='</tr>';
				
				$srNo++;
			}
		}
		return $output .='</tbody>';
	}
	
	public function list_item_condition()
	{
		$output='<thead>
					<th>#</th>
					<th>Condition</th>
					
					<th>Action</th>
				<thead><tbody>';
		$sql = "SELECT * FROM ad_item_condition";
		$res = parent::execQuery($sql);
		if($res && $res->num_rows>0)
		{
			$srNo=1;
			while($data = $res->fetch_assoc())
			{
				$ITEM_CONDITION_ID= $data['ITEM_CONDITION_ID'];
				$ITEM_CONDITION	 	= $data['ITEM_CONDITION'];
				$CREATED_BY 		= $data['CREATED_BY'];
				$CREATED_ON 		= $data['CREATED_ON'];
				$UPDATED_BY 		= $data['UPDATED_BY'];
				$UPDATED_ON 		= $data['UPDATED_ON'];
				
				$output .='<tr>';
				$output .= '<td>'.$srNo.'</td>';
				$output .= '<td>'.$ITEM_CONDITION.'</td>';
				$output .= '<td><a href="#" class="btn btn-xs btn-primary" onclick=""><i class="fa fa-pencil fa-1x"></i>
</a>
									<a href="#" class="btn btn-xs btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i>
</a></td>';
				$output .='</tr>';
				
				$srNo++;
			}
		}
		return $output .='</tbody>';
	}
	
}
?>