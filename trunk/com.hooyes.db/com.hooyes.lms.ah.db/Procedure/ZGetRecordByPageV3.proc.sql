-- DROP PROC ZGetRecordByPageV3
GO
-- =============================================
/*
最新后更新时间: 2009-9-22 by hooyes
exec ZGetRecordByPageV3
	@TableNames ='v_MyCourse',    -- 表名，可以是多个表，但不能用别名
	@PrimaryKey ='usn',           -- 主键，可以为空，但@Order为空时该值不能为空
	@Fields   ='',                -- 要取出的字段，可以是多个表的字段，可以为空，为空表示select *
	@PageSize =@oPageSize,        -- 每页记录数
	@CurrentPage =@oCurrentPage,  -- 当前页，0表示第1页
	@Filter  = @oFilter,          -- 条件，可以为空，不用填 where
	@Group  = '',                 -- 分组依据，可以为空，不用填 group by
	@Order  = 'course_id desc'    -- 排序，可以为空，为空默认按主键升序排列，不用填 order by
								  -- 专用分页存储过程分页例子 请勿修改!
*/
-- =============================================
CREATE PROCEDURE [dbo].[ZGetRecordByPageV3]
	@TableNames VARCHAR(700),       --表名，可以是多个表，但不能用别名
	@PrimaryKey VARCHAR(100),       --主键，可以为空，但@Order为空时该值不能为空
	@Fields    VARCHAR(700),        --要取出的字段，可以是多个表的字段，可以为空，为空表示select *
	@PageSize INT,                  --每页记录数
	@CurrentPage INT,               --当前页，0表示第1页
	@Filter VARCHAR(700) = '',      --条件，可以为空，不用填 where
	@Group VARCHAR(300) = '',       --分组依据，可以为空，不用填 group by
	@Order VARCHAR(300) = ''        --排序，可以为空，为空默认按主键升序排列，不用填 order by
AS
BEGIN
    DECLARE @SortColumn VARCHAR(250)
    DECLARE @Operator CHAR(2)
    DECLARE @SortTable VARCHAR(250)
    DECLARE @SortName VARCHAR(250)
    IF @Fields = ''
        SET @Fields = '*'
    IF @Filter = ''
        SET @Filter = 'WHERE 1=1'
    ELSE
        SET @Filter = 'WHERE ' +  @Filter
    IF @Group <>''
        SET @Group = 'GROUP BY ' + @Group

    IF @Order <> ''
    BEGIN
        DECLARE @pos1 INT, @pos2 INT
        SET @Order = REPLACE(REPLACE(@Order, ' asc', ' ASC'), ' desc', ' DESC')
        IF CHARINDEX(' DESC', @Order) > 0
            IF CHARINDEX(' ASC', @Order) > 0
            BEGIN
                IF CHARINDEX(' DESC', @Order) < CHARINDEX(' ASC', @Order)
                    SET @Operator = '<='
                ELSE
                    SET @Operator = '>='
            END
            ELSE
                SET @Operator = '<='
        ELSE
            SET @Operator = '>='
        SET @SortColumn = REPLACE(REPLACE(REPLACE(@Order, ' ASC', ''), ' DESC', ''), ' ', '')
        SET @pos1 = CHARINDEX(',', @SortColumn)
        IF @pos1 > 0
            SET @SortColumn = SUBSTRING(@SortColumn, 1, @pos1-1)
        SET @pos2 = CHARINDEX('.', @SortColumn)
        IF @pos2 > 0
        BEGIN
            SET @SortTable = SUBSTRING(@SortColumn, 1, @pos2-1)
            IF @pos1 > 0 
                SET @SortName = SUBSTRING(@SortColumn, @pos2+1, @pos1-@pos2-1)
            ELSE
                SET @SortName = SUBSTRING(@SortColumn, @pos2+1, LEN(@SortColumn)-@pos2)
        END
        ELSE
        BEGIN
            SET @SortTable = @TableNames
            SET @SortName = @SortColumn
        END
    END
    ELSE
    BEGIN
        SET @SortColumn = @PrimaryKey
        SET @SortTable = @TableNames
        SET @SortName = @SortColumn
        SET @Order = @SortColumn
        SET @Operator = '>='
    END

    DECLARE @type varchar(50)
    DECLARE @prec int
    SELECT @type=t.name, @prec=c.prec
    FROM sysobjects o 
    JOIN syscolumns c on o.id=c.id
    JOIN systypes t on c.xusertype=t.xusertype
    WHERE o.name = @SortTable AND c.name = @SortName
    IF CHARINDEX('char', @type) > 0
    SET @type = @type + '(' + CAST(@prec AS varchar) + ')'

    DECLARE @TopRows INT
    SET @TopRows = @PageSize * @CurrentPage + 1
    --print @TopRows
    --print @Operator
    EXEC('
        DECLARE @SortColumnBegin ' + @type + '
        SET ROWCOUNT ' + @TopRows + '
        SELECT @SortColumnBegin=' + @SortColumn + ' FROM  ' + @TableNames + ' ' + @Filter + ' ' + @Group + ' ORDER BY ' + @Order + '
        SET ROWCOUNT ' + @PageSize + '
        SELECT ' + @Fields + ' FROM  ' + @TableNames + ' ' + @Filter  + ' AND ' + @SortColumn + '' + @Operator + '@SortColumnBegin ' + @Group + ' ORDER BY ' + @Order + '    
    ')    

END




GO
