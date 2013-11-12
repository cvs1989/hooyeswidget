-- =============================================
-- Version:     1.0.0.1
-- Author:		hooyes
-- Create date: 2013-11-08
-- Update date: 2013-11-08
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[M_Get_SurveyList]
	@PageSize int = 100,
	@CurrentPage int = 1,
	@Filter varchar(700) = ''
AS
	DECLARE @Records int = 0
	SET @CurrentPage = @CurrentPage - 1;
	IF @CurrentPage < 0 
		SET @CurrentPage = 0
	EXEC ZGetRecordByPageV3
		@TableNames ='Survey',     
		@PrimaryKey ='ID',           
		@Fields   ='',                 
		@PageSize = @PageSize,         
		@CurrentPage = @CurrentPage,   
		@Filter  = @Filter,           
		@Group  = '',                  
		@Order  = ' ID DESC'   
RETURN 0
