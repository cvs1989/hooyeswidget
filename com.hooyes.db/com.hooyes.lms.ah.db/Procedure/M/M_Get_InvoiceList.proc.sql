-- DROP PROC [M_Get_InvoiceList]
GO
-- =============================================
-- Version:     1.0.0.1
-- Author:		hooyes
-- Create date: 2012-03-11
-- Update date: 2013-06-16
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[M_Get_InvoiceList]
	@PageSize int = 100,
	@CurrentPage int = 1,
	@Filter varchar(700) = ''
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
		@Filter  = @Filter,           
		@Group  = '',                  
		@Order  = ' IID DESC'   

RETURN 0