
$(document).ready(function(){
	var chart1 = new cfx.Chart();
		chart1.setGallery(cfx.Gallery.Pie);
		chart1.getAllSeries().getPointLabels().setVisible(true);
	
	var pie = chart1.getGalleryAttributes();
		pie.setExplodingMode(cfx.ExplodingMode.All);

		chart1.setDataSource(aDataEquipos); 
	var fields = chart1.getDataSourceSettings().getFields();

	var field1 = new cfx.FieldMap();
		field1.setName("Status");
		field1.setUsage(cfx.FieldUsage.RowHeading);
		fields.add(field1);

	var field2 = new cfx.FieldMap();
		field2.setName("Region");
		field2.setUsage(cfx.FieldUsage.ColumnHeading);
		fields.add(field2);

	var fieldVal = new cfx.FieldMap();
		fieldVal.setName("Usage");
		fieldVal.setUsage(cfx.FieldUsage.Value);
		fields.add(fieldVal);

	var crosstab = new cfx.data.CrosstabDataProvider();

		crosstab.setDataSource(chart1.getDataSource());
		chart1.setDataSource(crosstab);
	
	var data = chart1.getData();
		data.setSeries(1);

	chart1.getAnimations().getLoad().setEnabled(true);
	chart1.getAllSeries().getPointLabels().setVisible(true);
	chart1.create('divGrafica');
});