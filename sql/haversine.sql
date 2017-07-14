-- @author Dylan McDonald
-- @see https://github.com/dylan-mcdonald/data-design/blob/master/sql/haversine.sql#L35
-- adding a comment

-- drop the procedure if already defined
DROP FUNCTION IF EXISTS haversine;

-- procedure to calculate the distance between two points
-- @param POINT $origin point of origin
-- @param POINT $destination point heading out
-- @return DECIMAL distance between the points, in kilometers
CREATE FUNCTION haversine(origin POINT, destination POINT) RETURNS FLOAT
	BEGIN
		-- first, declare all variables; I don't think you can declare later
		DECLARE radius FLOAT;
		DECLARE latitudeAngle1 FLOAT;
		DECLARE latitudeAngle2 FLOAT;
		DECLARE latitudePhase FLOAT;
		DECLARE longitudePhase FLOAT;
		DECLARE alpha FLOAT;
		DECLARE corner FLOAT;
		DECLARE distance FLOAT;

		-- assign the variables that were declared & use them
		SET radius = 3939; -- radius of the earth in miles
		SET latitudeAngle1 = RADIANS(Y(origin));
		SET latitudeAngle2 = RADIANS(Y(destination));
		SET latitudePhase = RADIANS(Y(destination) - Y(origin));
		SET longitudePhase = RADIANS(X(destination) - X(origin));

		SET alpha = POW(SIN(latitudePhase / 2), 2)
						+ POW(SIN(longitudePhase / 2), 2)
						* COS(latitudeAngle1) * COS(latitudeAngle2);
		SET corner = 2 * ASIN(SQRT(alpha));
		SET distance = radius * corner;

		RETURN distance;
	END;
