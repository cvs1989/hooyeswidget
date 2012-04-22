DROP PROC [M_Get_MemberList]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-03-06
-- Update date: 2012-04-21
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[M_Get_MemberList]
	@PageSize int = 100,
	@CurrentPage int = 1
AS
	DECLARE @Records int = 0
	SET @CurrentPage = @CurrentPage - 1;
	IF @CurrentPage < 0 
		SET @CurrentPage = 0
	EXEC ZGetRecordByPageV3
		@TableNames ='v_m_member',     
		@PrimaryKey ='MID',           
		@Fields   ='',                 
		@PageSize = @PageSize,         
		@CurrentPage = @CurrentPage,   
		@Filter  = '',           
		@Group  = '',                  
		@Order  = ' MID DESC'   
	  
RETURN @Records