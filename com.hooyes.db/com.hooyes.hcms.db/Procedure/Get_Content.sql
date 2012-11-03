DROP PROC [Get_Content]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-11-03
-- Update date: 2012-11-03
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Get_Content]
	@CID int = 0,
	@PageSize int = 10,
	@CurrentPage int = 0,
	@Filter varchar(700) = ''
AS
	IF @CID > 0 
	BEGIN
		SELECT * FROM Hcms_Content WHERE CID = @CID
	END
	ELSE
	BEGIN

		EXECUTE ZGetRecordByPageV3
			@TableNames ='Hcms_Content',  
			@PrimaryKey ='CID',           
			@Fields   ='',                
			@PageSize    = @PageSize,        -- 每页记录数
			@CurrentPage = @CurrentPage,     -- 当前页，0表示第1页
			@Filter      = @Filter,          -- 条件，可以为空，不用填 where
			@Group  = '',                 
			@Order  = 'CID desc'           
	END
RETURN 0
