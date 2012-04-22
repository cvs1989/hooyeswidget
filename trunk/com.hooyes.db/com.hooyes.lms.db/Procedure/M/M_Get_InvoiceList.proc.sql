DROP PROC [M_Get_InvoiceList]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-03-11
-- Update date: 2012-04-22
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[M_Get_InvoiceList]
	@PageSize int = 100,
	@CurrentPage int = 1
AS
	DECLARE @Records int = 0
	SET @CurrentPage = @CurrentPage - 1;
	IF @CurrentPage < 0 
		SET @CurrentPage = 0
	EXEC ZGetRecordByPageV3
		@TableNames ='v_m_invoice',     
		@PrimaryKey ='IID',           
		@Fields   ='',                 
		@PageSize = @PageSize,         
		@CurrentPage = @CurrentPage,   
		@Filter  = '',           
		@Group  = '',                  
		@Order  = ' IID DESC'   

RETURN 0