import java.io.*;
import java.util.*;   //for List
import javax.servlet.*;
import javax.servlet.http.*;
import java.net.*;
import org.jdom.*;
import org.json.*;
import org.jdom.input.SAXBuilder; //for SAXBuilder


public class HelloWorldExample extends HttpServlet {
	public void doGet(HttpServletRequest request, HttpServletResponse response) 
							throws IOException, ServletException {
		response.setContentType("application/json;charset=UTF-8");
		PrintWriter out=response.getWriter();   
		String searchTitle = request.getParameter("title");
		String searchType = request.getParameter("type");
		
		String data = getPHPData(searchTitle, searchType);		
		
		try{
			SAXBuilder builder = new SAXBuilder();
			Document doc = builder.build(new StringReader(data));  //may throw JDOMException and IOException
			Element results = doc.getRootElement();
			List resultList = results.getChildren("result");
			
			JSONObject resultsJson = new JSONObject();							
			if(searchType.equals("artists")) resultsJson = parseArtists(resultList);
			else if(searchType.equals("albums")) resultsJson = parseAlbums(resultList);
			else if(searchType.equals("songs")) resultsJson = parseSongs(resultList);			
			
			out.println(resultsJson.toString());
			out.close();
		}
		
		catch(JDOMException e) {
			e.printStackTrace();		
		}
		catch(IOException e) {
			e.printStackTrace();
		}	
		
		
		
	}
	
	public static String getPHPData(String searchTitle, String searchType) {
		
		try{
			String urlStr = "http://cs-server.usc.edu:35265/cgi-bin/HW8.php?title="+searchTitle+"&type="+searchType;
			URL url = new URL(urlStr); //may throw MalformedURLException
			URLConnection urlConn= url.openConnection();

			InputStreamReader iSR = new InputStreamReader(urlConn.getInputStream(), "UTF-8");
			BufferedReader br = new BufferedReader(iSR); //may throw IOException

			String buffer;
			String data = "";			
			while((buffer = br.readLine())!=null) {
				data = data + buffer+"\n";   //http://www.codingdiary.com/developers/developers/diary/javaapi/java/text/SampleCode
                                      //URLopenStreamExampleCode.html
			}

			br.close();	
			iSR.close();
			return data;			
		}
		catch(MalformedURLException e) {
			e.printStackTrace();
			return "False";
		}
		catch(IOException e) {
			e.printStackTrace();
			return "False";
		}	
		
	}

	
	public JSONObject parseArtists(List resultList) {
		
		JSONArray jsons = new JSONArray();
		for (int i = 0; i < resultList.size(); i++) {
			JSONObject json = new JSONObject();
			Element resultNode = (Element) resultList.get(i);
              
			json.put("cover", resultNode.getAttributeValue("cover"));
			json.put("title", resultNode.getAttributeValue("name"));
			json.put("genre", resultNode.getAttributeValue("genre"));
			json.put("year", resultNode.getAttributeValue("year"));
			json.put("details", resultNode.getAttributeValue("details"));
			jsons.put(json);
		
		}
		JSONObject resultJson = new JSONObject();
		resultJson.put("result", jsons);
                                              
		JSONObject resultsJson = new JSONObject();
		resultsJson.put("results", resultJson);	
		return resultsJson;		
		
	}


	public JSONObject parseAlbums(List resultList){
		
		JSONArray jsons = new JSONArray();
		for (int i = 0; i < resultList.size(); i++) {
			JSONObject json = new JSONObject();
			Element resultNode = (Element) resultList.get(i);
              
			json.put("cover", resultNode.getAttributeValue("cover"));
			json.put("title", resultNode.getAttributeValue("title"));
			json.put("artist", resultNode.getAttributeValue("artist"));
			json.put("genre", resultNode.getAttributeValue("genre"));
			json.put("year", resultNode.getAttributeValue("year"));
			json.put("details", resultNode.getAttributeValue("details"));
			jsons.put(json);
		
		}
		JSONObject resultJson = new JSONObject();
		resultJson.put("result", jsons);
                                               
		JSONObject resultsJson = new JSONObject();
		resultsJson.put("results", resultJson);	
		return resultsJson;	
		
	}



	public JSONObject parseSongs(List resultList) {		

		JSONArray jsons = new JSONArray();
		for (int i = 0; i < resultList.size(); i++) {
			JSONObject json = new JSONObject();
			Element resultNode = (Element) resultList.get(i);
              
			json.put("sample", resultNode.getAttributeValue("sample"));
			json.put("title", resultNode.getAttributeValue("title"));
			json.put("performer", resultNode.getAttributeValue("performer"));
			json.put("composer", resultNode.getAttributeValue("composer"));
			json.put("details", resultNode.getAttributeValue("details"));
			jsons.put(json);
		
		}
		JSONObject resultJson = new JSONObject();
		resultJson.put("result", jsons);
                                               
		JSONObject resultsJson = new JSONObject();
		resultsJson.put("results", resultJson);	
		return resultsJson;
		
	}
}










