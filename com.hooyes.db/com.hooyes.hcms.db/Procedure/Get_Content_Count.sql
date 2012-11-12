DROP PROC [Get_Content_Count]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-11-11
-- Update date: 2012-11-11
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Get_Content_Count]
	@CID int = 0,
	@PageSize int = 10,
	@CurrentPage int = 0,
	@Filter varchar(700) = ''
AS
	IF @Filter = ''
	BEGIN
		SELECT COUNT(1) FROM Hcms_Content
	END
	ELSE
	BEGIN
	/*
		DECLARE @SQLString nvarchar(3000)
			   ,@ParamDefinition nvarchar(500)
			   ,@C_count

		SET @ParamDefinition = N'@cid int, @criteria_count int OUTPUT';
		EXECUTE sp_executesql @SQLString, @ParamDefinition, 
							  @cid = @cid, 
							  @criteria_count = @C_count OUTPUT;	
	*/

	DECLARE @SQLString nvarchar(3000)
	SET @SQLString = 'SELECT COUNT(1) FROM Hcms_Content WHERE ' + @Filter
	EXECUTE sp_executesql @SQLString	
	END
RETURN 0
